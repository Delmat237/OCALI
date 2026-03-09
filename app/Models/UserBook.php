<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class UserBook extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'book_id',
        'subscription_id',
        'current_page',
        'total_pages',
        'progress_percent',
        'reading_time_minutes',
        'last_read_at',
        'completed_at',
        'status',
        'bookmarks',
        'reader_settings',
    ];

    protected function casts(): array
    {
        return [
            'progress_percent' => 'decimal:2',
            'last_read_at' => 'datetime',
            'completed_at' => 'datetime',
            'bookmarks' => 'array',
            'reader_settings' => 'array',
        ];
    }

    // Scopes
    public function scopeReading($query)
    {
        return $query->where('status', 'reading');
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    // Relationships
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function book(): BelongsTo
    {
        return $this->belongsTo(Book::class);
    }

    public function subscription(): BelongsTo
    {
        return $this->belongsTo(Subscription::class);
    }

    public function readingSessions(): HasMany
    {
        return $this->hasMany(ReadingSession::class);
    }

    // Methods
    public function updateProgress(int $currentPage): void
    {
        $totalPages = $this->total_pages ?: $this->book->pages_count;

        $progress = $totalPages > 0 ? ($currentPage / $totalPages) * 100 : 0;

        $this->update([
            'current_page' => $currentPage,
            'total_pages' => $totalPages,
            'progress_percent' => min(100, $progress),
            'last_read_at' => now(),
            'status' => $progress >= 100 ? 'completed' : 'reading',
            'completed_at' => $progress >= 100 ? now() : null,
        ]);

        // Update user stats if completed
        if ($progress >= 100 && $this->wasChanged('status')) {
            $this->user->increment('books_read');
            $this->book->incrementReads();
        }
    }

    public function addReadingTime(int $minutes): void
    {
        $this->increment('reading_time_minutes', $minutes);
        $this->user->increment('reading_time_minutes', $minutes);
        $this->update(['last_read_at' => now()]);
    }

    public function addBookmark(int $page, string $note = null): void
    {
        $bookmarks = $this->bookmarks ?? [];
        $bookmarks[] = [
            'page' => $page,
            'note' => $note,
            'created_at' => now()->toISOString(),
        ];
        $this->update(['bookmarks' => $bookmarks]);
    }

    public function removeBookmark(int $index): void
    {
        $bookmarks = $this->bookmarks ?? [];
        unset($bookmarks[$index]);
        $this->update(['bookmarks' => array_values($bookmarks)]);
    }

    public function isCompleted(): bool
    {
        return $this->status === 'completed';
    }

    public function getFormattedReadingTimeAttribute(): string
    {
        $hours = floor($this->reading_time_minutes / 60);
        $minutes = $this->reading_time_minutes % 60;

        if ($hours > 0) {
            return "{$hours}h {$minutes}min";
        }

        return "{$minutes} min";
    }
}
