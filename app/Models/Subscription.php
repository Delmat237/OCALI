<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Subscription extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'plan_id',
        'amount_paid',
        'platform_commission',
        'starts_at',
        'ends_at',
        'selected_book_ids',
        'books_remaining',
        'status',
        'payment_method',
        'payment_reference',
    ];

    protected function casts(): array
    {
        return [
            'amount_paid' => 'decimal:2',
            'platform_commission' => 'decimal:2',
            'starts_at' => 'datetime',
            'ends_at' => 'datetime',
            'selected_book_ids' => 'array',
        ];
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('status', 'active')
            ->where('ends_at', '>', now());
    }

    public function scopeExpired($query)
    {
        return $query->where('ends_at', '<=', now())
            ->orWhere('status', 'expired');
    }

    // Relationships
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function plan(): BelongsTo
    {
        return $this->belongsTo(SubscriptionPlan::class, 'plan_id');
    }

    // Helpers
    public function isActive(): bool
    {
        return $this->status === 'active' && $this->ends_at > now();
    }

    public function isExpired(): bool
    {
        return $this->ends_at <= now() || $this->status === 'expired';
    }

    public function hasRemainingBooks(): bool
    {
        return $this->books_remaining > 0;
    }

    public function decrementBooks(): void
    {
        if ($this->books_remaining > 0) {
            $this->decrement('books_remaining');
        }
    }

    public function addSelectedBook(int $bookId): void
    {
        $books = $this->selected_book_ids ?? [];
        if (!in_array($bookId, $books)) {
            $books[] = $bookId;
            $this->update(['selected_book_ids' => $books]);
        }
    }

    public function getDaysRemainingAttribute(): int
    {
        return max(0, now()->diffInDays($this->ends_at, false));
    }

    public function getProgressPercentAttribute(): float
    {
        $totalDays = $this->starts_at->diffInDays($this->ends_at);
        $elapsed = $this->starts_at->diffInDays(now());

        if ($totalDays === 0) return 100;

        return min(100, ($elapsed / $totalDays) * 100);
    }

    public function getBooksUsedAttribute(): int
    {
        $plan = $this->plan;
        return $plan ? $plan->books_limit - $this->books_remaining : 0;
    }

    public function getStatusLabelAttribute(): string
    {
        if ($this->isExpired()) {
            return __('messages.subscription_expired');
        }

        return match($this->status) {
            'active' => __('messages.subscription_active'),
            'cancelled' => __('messages.subscription_cancelled'),
            default => $this->status,
        };
    }

    public function getStatusColorAttribute(): string
    {
        if ($this->isExpired()) return 'red';

        return match($this->status) {
            'active' => 'green',
            'cancelled' => 'gray',
            default => 'yellow',
        };
    }
}
