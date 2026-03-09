<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Chronicle extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'user_id',
        'book_id',
        'title',
        'slug',
        'content',
        'excerpt',
        'cover_image',
        'status',
        'published_at',
        'views_count',
        'likes_count',
    ];

    protected function casts(): array
    {
        return [
            'published_at' => 'datetime',
        ];
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($chronicle) {
            if (empty($chronicle->slug)) {
                $chronicle->slug = Str::slug($chronicle->title) . '-' . Str::random(6);
            }
            if (empty($chronicle->excerpt) && $chronicle->content) {
                $chronicle->excerpt = Str::limit(strip_tags($chronicle->content), 200);
            }
        });
    }

    public function scopePublished($query)
    {
        return $query->where('status', 'published')->whereNotNull('published_at');
    }

    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function book(): BelongsTo
    {
        return $this->belongsTo(Book::class);
    }

    public function comments(): HasMany
    {
        return $this->hasMany(ChronicleComment::class);
    }

    public function approvedComments(): HasMany
    {
        return $this->comments()->where('status', 'approved');
    }

    public function incrementViews(): void
    {
        $this->increment('views_count');
    }
}
