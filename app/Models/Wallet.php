<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\DB;

class Wallet extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'balance',
        'total_earned',
        'total_withdrawn',
        'pending_amount',
        'currency',
    ];

    protected function casts(): array
    {
        return [
            'balance' => 'decimal:2',
            'total_earned' => 'decimal:2',
            'total_withdrawn' => 'decimal:2',
            'pending_amount' => 'decimal:2',
        ];
    }

    // Relationships
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function transactions(): HasMany
    {
        return $this->hasMany(WalletTransaction::class);
    }

    public function withdrawalRequests(): HasMany
    {
        return $this->hasMany(WithdrawalRequest::class);
    }

    // Methods
    public function credit(float $amount, string $description, ?int $subscriptionId = null, ?int $bookId = null): WalletTransaction
    {
        return DB::transaction(function () use ($amount, $description, $subscriptionId, $bookId) {
            $balanceBefore = $this->balance;

            $this->increment('balance', $amount);
            $this->increment('total_earned', $amount);

            return WalletTransaction::create([
                'wallet_id' => $this->id,
                'user_id' => $this->user_id,
                'type' => 'earning',
                'amount' => $amount,
                'balance_before' => $balanceBefore,
                'balance_after' => $this->fresh()->balance,
                'description' => $description,
                'subscription_id' => $subscriptionId,
                'book_id' => $bookId,
                'status' => 'completed',
            ]);
        });
    }

    public function debit(float $amount, string $description, string $type = 'withdrawal'): WalletTransaction
    {
        return DB::transaction(function () use ($amount, $description, $type) {
            if ($this->balance < $amount) {
                throw new \Exception(__('messages.insufficient_balance'));
            }

            $balanceBefore = $this->balance;

            $this->decrement('balance', $amount);

            if ($type === 'withdrawal') {
                $this->increment('total_withdrawn', $amount);
            }

            return WalletTransaction::create([
                'wallet_id' => $this->id,
                'user_id' => $this->user_id,
                'type' => $type,
                'amount' => -$amount,
                'balance_before' => $balanceBefore,
                'balance_after' => $this->fresh()->balance,
                'description' => $description,
                'status' => 'completed',
            ]);
        });
    }

    public function canWithdraw(float $amount): bool
    {
        $minThreshold = Setting::getValue('min_withdrawal_threshold', 5000);
        return $this->balance >= $amount && $amount >= $minThreshold;
    }

    public function getAvailableBalanceAttribute(): float
    {
        return max(0, $this->balance - $this->pending_amount);
    }

    public function getFormattedBalanceAttribute(): string
    {
        return number_format($this->balance, 0, ',', ' ') . ' ' . $this->currency;
    }
}
