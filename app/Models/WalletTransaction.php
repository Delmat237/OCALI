<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WalletTransaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'wallet_id',
        'user_id',
        'type',
        'amount',
        'balance_before',
        'balance_after',
        'description',
        'reference',
        'subscription_id',
        'book_id',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'amount' => 'decimal:2',
            'balance_before' => 'decimal:2',
            'balance_after' => 'decimal:2',
        ];
    }

    // Scopes
    public function scopeEarnings($query)
    {
        return $query->where('type', 'earning');
    }

    public function scopeWithdrawals($query)
    {
        return $query->where('type', 'withdrawal');
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    // Relationships
    public function wallet(): BelongsTo
    {
        return $this->belongsTo(Wallet::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function subscription(): BelongsTo
    {
        return $this->belongsTo(Subscription::class);
    }

    public function book(): BelongsTo
    {
        return $this->belongsTo(Book::class);
    }

    // Helpers
    public function isPositive(): bool
    {
        return $this->amount > 0;
    }

    public function getTypeLabelAttribute(): string
    {
        return match($this->type) {
            'earning' => __('messages.transaction_earning'),
            'withdrawal' => __('messages.transaction_withdrawal'),
            'bonus' => __('messages.transaction_bonus'),
            'adjustment' => __('messages.transaction_adjustment'),
            default => $this->type,
        };
    }

    public function getTypeIconAttribute(): string
    {
        return match($this->type) {
            'earning' => 'arrow-down',
            'withdrawal' => 'arrow-up',
            'bonus' => 'gift',
            'adjustment' => 'adjustments',
            default => 'currency-dollar',
        };
    }

    public function getTypeColorAttribute(): string
    {
        return match($this->type) {
            'earning' => 'green',
            'withdrawal' => 'red',
            'bonus' => 'blue',
            'adjustment' => 'yellow',
            default => 'gray',
        };
    }

    public function getFormattedAmountAttribute(): string
    {
        $sign = $this->amount >= 0 ? '+' : '';
        return $sign . number_format($this->amount, 0, ',', ' ') . ' XAF';
    }
}
