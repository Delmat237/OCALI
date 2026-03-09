<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use App\Models\WithdrawalRequest;
use App\Services\NokashService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class WalletController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $wallet = $user->getOrCreateWallet();

        $recentTransactions = $wallet->transactions()
            ->with('book')
            ->orderByDesc('created_at')
            ->take(10)
            ->get();

        $pendingWithdrawals = WithdrawalRequest::where('user_id', $user->id)
            ->where('status', 'pending')
            ->count();

        $minThreshold = Setting::getValue('min_withdrawal_threshold', 5000);

        return view('wallet.index', compact('wallet', 'recentTransactions', 'pendingWithdrawals', 'minThreshold'));
    }

    public function transactions()
    {
        $wallet = Auth::user()->getOrCreateWallet();

        $transactions = $wallet->transactions()
            ->with('book')
            ->orderByDesc('created_at')
            ->paginate(20);

        return view('wallet.transactions', compact('wallet', 'transactions'));
    }

    public function withdrawForm()
    {
        $wallet = Auth::user()->getOrCreateWallet();
        $minThreshold = Setting::getValue('min_withdrawal_threshold', 5000);

        return view('wallet.withdraw', compact('wallet', 'minThreshold'));
    }

    public function withdraw(Request $request)
    {
        $validated = $request->validate([
            'amount' => 'required|numeric|min:1',
            'payment_method' => 'required|in:mtn,orange',
            'phone' => 'required|string|max:20',
        ]);

        $user = Auth::user();
        $wallet = $user->getOrCreateWallet();

        $minThreshold = Setting::getValue('min_withdrawal_threshold', 5000);

        if ($validated['amount'] < $minThreshold) {
            return back()->with('error', __('messages.min_withdrawal', ['amount' => number_format($minThreshold, 0, ',', ' ')]));
        }

        if ($wallet->balance < $validated['amount']) {
            return back()->with('error', __('messages.insufficient_balance'));
        }

        // Check reading threshold for authors
        $minReading = Setting::getValue('min_reading_threshold', 5);
        $totalBooksRead = $user->books()->sum('reads_count');
        if ($totalBooksRead < $minReading) {
            return back()->with('error', __('messages.reading_threshold_not_met', ['count' => $minReading]));
        }

        WithdrawalRequest::create([
            'user_id' => $user->id,
            'wallet_id' => $wallet->id,
            'amount' => $validated['amount'],
            'payment_method' => $validated['payment_method'],
            'phone' => $validated['phone'],
            'status' => 'pending',
        ]);

        // Reserve the amount
        $wallet->increment('pending_amount', $validated['amount']);

        return redirect()->route('author.wallet')
            ->with('success', __('messages.withdrawal_requested'));
    }
}
