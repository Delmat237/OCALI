<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Setting extends Model
{
    protected $fillable = [
        'key',
        'value',
        'type',
        'group',
        'description',
        'is_public',
    ];

    protected function casts(): array
    {
        return [
            'is_public' => 'boolean',
        ];
    }

    // Cache key
    private static function cacheKey(string $key): string
    {
        return 'setting_' . $key;
    }

    // Get a setting value
    public static function getValue(string $key, $default = null)
    {
        return Cache::remember(self::cacheKey($key), 3600, function () use ($key, $default) {
            $setting = self::where('key', $key)->first();

            if (!$setting) {
                return $default;
            }

            return self::castValue($setting->value, $setting->type);
        });
    }

    // Set a setting value
    public static function setValue(string $key, $value, string $type = 'string', string $group = 'general'): self
    {
        $setting = self::updateOrCreate(
            ['key' => $key],
            [
                'value' => is_array($value) ? json_encode($value) : (string) $value,
                'type' => $type,
                'group' => $group,
            ]
        );

        Cache::forget(self::cacheKey($key));

        return $setting;
    }

    // Cast value based on type
    private static function castValue($value, string $type)
    {
        return match($type) {
            'integer', 'int' => (int) $value,
            'float', 'double' => (float) $value,
            'boolean', 'bool' => filter_var($value, FILTER_VALIDATE_BOOLEAN),
            'array', 'json' => json_decode($value, true),
            default => $value,
        };
    }

    // Scopes
    public function scopeGroup($query, string $group)
    {
        return $query->where('group', $group);
    }

    public function scopePublic($query)
    {
        return $query->where('is_public', true);
    }

    // Helper to get typed value
    public function getTypedValueAttribute()
    {
        return self::castValue($this->value, $this->type);
    }

    // Default settings
    public static function defaults(): array
    {
        return [
            'platform_commission_rate' => ['value' => '0.20', 'type' => 'float', 'group' => 'payments'],
            'author_share_per_subscription' => ['value' => '500', 'type' => 'integer', 'group' => 'payments'],
            'min_withdrawal_threshold' => ['value' => '5000', 'type' => 'integer', 'group' => 'payments'],
            'min_reading_threshold' => ['value' => '5', 'type' => 'integer', 'group' => 'reading'],
            'welcome_book_id' => ['value' => null, 'type' => 'integer', 'group' => 'books'],
            'site_name' => ['value' => 'OCaLi', 'type' => 'string', 'group' => 'general'],
            'site_description' => ['value' => 'Your Online Library', 'type' => 'string', 'group' => 'general'],
            'contact_email' => ['value' => 'ocali597198@gmail.com', 'type' => 'string', 'group' => 'general'],
            'newsletter_frequency' => ['value' => 'weekly', 'type' => 'string', 'group' => 'newsletter'],
        ];
    }

    // Initialize default settings
    public static function initializeDefaults(): void
    {
        foreach (self::defaults() as $key => $config) {
            if (!self::where('key', $key)->exists()) {
                self::create([
                    'key' => $key,
                    'value' => $config['value'],
                    'type' => $config['type'],
                    'group' => $config['group'],
                ]);
            }
        }
    }
}
