<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BookReport extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'book_id',
        'reason',
        'description',
        'status',
        'admin_response',
        'action_taken',
        'handled_by',
        'handled_at',
    ];

    protected function casts(): array
    {
        return [
            'handled_at' => 'datetime',
        ];
    }

    // Scopes
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeInvestigating($query)
    {
        return $query->where('status', 'investigating');
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

    public function handler(): BelongsTo
    {
        return $this->belongsTo(User::class, 'handled_by');
    }

    // Helpers
    public function getReasonLabelAttribute(): string
    {
        return match($this->reason) {
            'copyright' => __('messages.report_copyright'),
            'inappropriate_content' => __('messages.report_inappropriate'),
            'spam' => __('messages.report_spam'),
            'misleading' => __('messages.report_misleading'),
            'quality_issues' => __('messages.report_quality'),
            'other' => __('messages.report_other'),
            default => $this->reason,
        };
    }

    public function getStatusLabelAttribute(): string
    {
        return match($this->status) {
            'pending' => __('messages.report_status_pending'),
            'investigating' => __('messages.report_status_investigating'),
            'resolved' => __('messages.report_status_resolved'),
            'dismissed' => __('messages.report_status_dismissed'),
            default => $this->status,
        };
    }

    public function getStatusColorAttribute(): string
    {
        return match($this->status) {
            'pending' => 'yellow',
            'investigating' => 'blue',
            'resolved' => 'green',
            'dismissed' => 'gray',
            default => 'gray',
        };
    }

    public function getActionLabelAttribute(): string
    {
        return match($this->action_taken) {
            'none' => __('messages.action_none'),
            'warning' => __('messages.action_warning'),
            'book_removed' => __('messages.action_book_removed'),
            'author_banned' => __('messages.action_author_banned'),
            default => '-',
        };
    }
}
