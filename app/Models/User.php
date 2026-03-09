<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'avatar',
        'phone',
        'bio',
        'role',
        'backend_user_id',
        'backend_token',
        'google_id',
        'facebook_id',
        'locale',
        'theme',
        'newsletter_subscribed',
        'books_read',
        'reading_time_minutes',
        'is_active',
        'last_login_at',
    ];

    protected $hidden = [
        'password',
        'remember_token',
        'google_id',
        'facebook_id',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'last_login_at' => 'datetime',
            'password' => 'hashed',
            'newsletter_subscribed' => 'boolean',
            'is_active' => 'boolean',
        ];
    }

    // Roles
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isAuthor(): bool
    {
        return in_array($this->role, ['author', 'editor', 'admin']);
    }

    public function isEditor(): bool
    {
        return in_array($this->role, ['editor', 'admin']);
    }

    public function isReader(): bool
    {
        return $this->role === 'reader';
    }

    // Relationships
    public function books(): HasMany
    {
        return $this->hasMany(Book::class, 'author_id');
    }

    public function publishedBooks(): HasMany
    {
        return $this->books()->where('status', 'approved');
    }

    public function subscriptions(): HasMany
    {
        return $this->hasMany(Subscription::class);
    }

    public function activeSubscription(): HasOne
    {
        return $this->hasOne(Subscription::class)
            ->where('status', 'active')
            ->where('ends_at', '>', now())
            ->latest();
    }

    public function wallet(): HasOne
    {
        return $this->hasOne(Wallet::class);
    }

    public function userBooks(): HasMany
    {
        return $this->hasMany(UserBook::class);
    }

    public function readingHistory(): HasMany
    {
        return $this->hasMany(UserBook::class)->orderByDesc('last_read_at');
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(BookReview::class);
    }

    public function chronicles(): HasMany
    {
        return $this->hasMany(Chronicle::class);
    }

    public function reports(): HasMany
    {
        return $this->hasMany(BookReport::class);
    }

    public function withdrawalRequests(): HasMany
    {
        return $this->hasMany(WithdrawalRequest::class);
    }

    public function monthlyReports(): HasMany
    {
        return $this->hasMany(MonthlyReport::class);
    }

    // Helpers
    public function hasActiveSubscription(): bool
    {
        return $this->activeSubscription()->exists();
    }

    public function canReadBooks(): bool
    {
        $subscription = $this->activeSubscription;
        return $subscription && $subscription->books_remaining > 0;
    }

    public function getOrCreateWallet(): Wallet
    {
        return $this->wallet ?? Wallet::create([
            'user_id' => $this->id,
            'balance' => 0,
            'total_earned' => 0,
            'total_withdrawn' => 0,
        ]);
    }

    public function getAvatarUrlAttribute(): string
    {
        if ($this->avatar) {
            return str_starts_with($this->avatar, 'http')
                ? $this->avatar
                : asset('storage/' . $this->avatar);
        }

        return 'https://ui-avatars.com/api/?name=' . urlencode($this->name) . '&background=4169E1&color=fff';
    }

    public function getRoleLabelAttribute(): string
    {
        return match($this->role) {
            'admin' => __('messages.role_admin'),
            'author' => __('messages.role_author'),
            'editor' => __('messages.role_editor'),
            default => __('messages.role_reader'),
        };
    }
}
