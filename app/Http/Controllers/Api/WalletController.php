<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WalletController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $wallet = $user->getOrCreateWallet();

        $transactions = $wallet->transactions()
            ->with('book')
            ->orderByDesc('created_at')
            ->take(20)
            ->get();

        return response()->json([
            'balance' => $wallet->balance,
            'pending_amount' => $wallet->pending_amount,
            'transactions' => $transactions
        ]);
    }
}
