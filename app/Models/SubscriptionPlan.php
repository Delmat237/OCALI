<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class SubscriptionPlan extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'name_en',
        'slug',
        'description',
        'type',
        'duration',
        'duration_days',
        'price',
        'price_eur',
        'books_limit',
        'publications_limit',
        'platform_commission_rate',
        'features',
        'is_popular',
        'is_active',
        'order',
    ];

    protected function casts(): array
    {
        return [
            'price' => 'decimal:2',
            'price_eur' => 'decimal:2',
            'platform_commission_rate' => 'decimal:4',
            'features' => 'array',
            'is_popular' => 'boolean',
            'is_active' => 'boolean',
        ];
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($plan) {
            if (empty($plan->slug)) {
                $plan->slug = Str::slug($plan->name . '-' . $plan->type . '-' . $plan->duration);
            }
        });
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeForReaders($query)
    {
        return $query->where('type', 'reader');
    }

    public function scopeForAuthors($query)
    {
        return $query->where('type', 'author');
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('order')->orderBy('price');
    }

    // Relationships
    public function subscriptions(): HasMany
    {
        return $this->hasMany(Subscription::class, 'plan_id');
    }

    // Helpers
    public function getLocalizedNameAttribute(): string
    {
        $locale = app()->getLocale();
        return $locale === 'en' && $this->name_en ? $this->name_en : $this->name;
    }

    public function getDurationLabelAttribute(): string
    {
        return match($this->duration) {
            'monthly' => __('messages.monthly'),
            'quarterly' => __('messages.quarterly'),
            'yearly' => __('messages.yearly'),
            default => $this->duration,
        };
    }

    public function getFormattedPriceAttribute(): string
    {
        return number_format($this->price, 0, ',', ' ') . ' XAF';
    }

    public function getPlatformCommission(): float
    {
        return $this->price * $this->platform_commission_rate;
    }

    public function getAuthorShare(): float
    {
        return $this->price - $this->getPlatformCommission();
    }

    public function isForReaders(): bool
    {
        return $this->type === 'reader';
    }

    public function isForAuthors(): bool
    {
        return $this->type === 'author';
    }
}
