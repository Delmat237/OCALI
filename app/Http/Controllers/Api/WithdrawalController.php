<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Withdrawal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WithdrawalController extends Controller
{
    public function index()
    {
        $withdrawals = Withdrawal::where('user_id', Auth::id())
            ->orderByDesc('created_at')
            ->get();

        return response()->json($withdrawals);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'amount' => 'required|numeric|min:1000',
            'method' => 'required|in:orange_money,mtn_momo,bank_transfer',
            'phone' => 'required_if:method,orange_money,mtn_momo|string',
            'bank_details' => 'required_if:method,bank_transfer|string',
        ]);

        $user = Auth::user();
        $wallet = $user->getOrCreateWallet();

        if ($wallet->balance < $validated['amount']) {
            return response()->json(['message' => 'insufficient_balance'], 400);
        }

        $withdrawal = Withdrawal::create([
            'user_id' => $user->id,
            'amount' => $validated['amount'],
            'method' => $validated['method'],
            'phone' => $validated['phone'] ?? null,
            'bank_details' => $validated['bank_details'] ?? null,
            'status' => 'pending',
        ]);

        return response()->json($withdrawal, 201);
    }
}
