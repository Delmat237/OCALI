<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MonthlyReport extends Model
{
    protected $fillable = [
        'user_id',
        'year',
        'month',
        'books_read',
        'reading_time_minutes',
        'earnings',
        'data',
        'sent_at',
    ];

    protected function casts(): array
    {
        return [
            'earnings' => 'decimal:2',
            'data' => 'array',
            'sent_at' => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function getMonthNameAttribute(): string
    {
        return \Carbon\Carbon::create($this->year, $this->month, 1)->translatedFormat('F Y');
    }

    public function getFormattedReadingTimeAttribute(): string
    {
        $hours = floor($this->reading_time_minutes / 60);
        $minutes = $this->reading_time_minutes % 60;
        return $hours > 0 ? "{$hours}h {$minutes}min" : "{$minutes} min";
    }
}
