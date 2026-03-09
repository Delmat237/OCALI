<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Book extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'author_id',
        'publisher',
        'category_id',
        'title',
        'slug',
        'description',
        'cover_image',
        'file_path',
        'file_type',
        'pages_count',
        'isbn',
        'language',
        'type',
        'doi',
        'keywords',
        'status',
        'rejection_reason',
        'published_at',
        'is_premium',
        'is_featured',
        'is_free_welcome_book',
        'views_count',
        'reads_count',
        'downloads_count',
        'average_rating',
        'reviews_count',
    ];

    protected function casts(): array
    {
        return [
            'keywords' => 'array',
            'published_at' => 'datetime',
            'is_premium' => 'boolean',
            'is_featured' => 'boolean',
            'is_free_welcome_book' => 'boolean',
            'average_rating' => 'decimal:2',
        ];
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($book) {
            if (empty($book->slug)) {
                $book->slug = Str::slug($book->title) . '-' . Str::random(6);
            }
        });
    }

    // Scopes
    public function scopePublished($query)
    {
        return $query->where('status', 'approved')
            ->whereNotNull('published_at');
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    public function scopePremium($query)
    {
        return $query->where('is_premium', true);
    }

    public function scopeOfType($query, string $type)
    {
        return $query->where('type', $type);
    }

    public function scopeWelcomeBook($query)
    {
        return $query->where('is_free_welcome_book', true);
    }

    // Relationships
    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(BookReview::class);
    }

    public function approvedReviews(): HasMany
    {
        return $this->reviews()->where('status', 'approved');
    }

    public function reports(): HasMany
    {
        return $this->hasMany(BookReport::class);
    }

    public function userBooks(): HasMany
    {
        return $this->hasMany(UserBook::class);
    }

    public function readers(): HasMany
    {
        return $this->userBooks();
    }

    public function chronicles(): HasMany
    {
        return $this->hasMany(Chronicle::class);
    }

    // Helpers
    public function isPublished(): bool
    {
        return $this->status === 'approved' && $this->published_at !== null;
    }

    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    public function getCoverUrlAttribute(): string
    {
        return $this->cover_image
            ? asset('storage/' . $this->cover_image)
            : asset('images/default-book-cover.png');
    }

    public function getFileUrlAttribute(): string
    {
        return asset('storage/' . $this->file_path);
    }

    public function getTypeLabelAttribute(): string
    {
        return match($this->type) {
            'scientific_review' => __('messages.type_scientific_review'),
            'chronicle' => __('messages.type_chronicle'),
            default => __('messages.type_book'),
        };
    }

    public function getStatusLabelAttribute(): string
    {
        return match($this->status) {
            'draft' => __('messages.status_draft'),
            'pending' => __('messages.status_pending'),
            'approved' => __('messages.status_approved'),
            'rejected' => __('messages.status_rejected'),
            default => $this->status,
        };
    }

    public function getStatusColorAttribute(): string
    {
        return match($this->status) {
            'draft' => 'gray',
            'pending' => 'yellow',
            'approved' => 'green',
            'rejected' => 'red',
            default => 'gray',
        };
    }

    public function incrementViews(): void
    {
        $this->increment('views_count');
    }

    public function incrementReads(): void
    {
        $this->increment('reads_count');
    }

    public function updateRating(): void
    {
        $avg = $this->reviews()->where('status', 'approved')->avg('rating');
        $count = $this->reviews()->where('status', 'approved')->count();

        $this->update([
            'average_rating' => $avg ?? 0,
            'reviews_count' => $count,
        ]);
    }
}
