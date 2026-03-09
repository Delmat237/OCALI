<?php

use App\Models\Setting;

if (!function_exists('setting')) {
    /**
     * Get a setting value
     */
    function setting(string $key, $default = null)
    {
        return Setting::getValue($key, $default);
    }
}

if (!function_exists('format_price')) {
    /**
     * Format price with currency
     */
    function format_price(float $amount, string $currency = 'XAF'): string
    {
        return number_format($amount, 0, ',', ' ') . ' ' . $currency;
    }
}

if (!function_exists('format_reading_time')) {
    /**
     * Format reading time in minutes to human readable
     */
    function format_reading_time(int $minutes): string
    {
        if ($minutes < 60) {
            return $minutes . ' min';
        }

        $hours = floor($minutes / 60);
        $remainingMinutes = $minutes % 60;

        if ($remainingMinutes === 0) {
            return $hours . 'h';
        }

        return $hours . 'h ' . $remainingMinutes . 'min';
    }
}

if (!function_exists('format_number')) {
    /**
     * Format large numbers (1K, 1M, etc.)
     */
    function format_number(int $number): string
    {
        if ($number >= 1000000) {
            return round($number / 1000000, 1) . 'M';
        }

        if ($number >= 1000) {
            return round($number / 1000, 1) . 'K';
        }

        return (string) $number;
    }
}

if (!function_exists('get_locale')) {
    /**
     * Get current locale
     */
    function get_locale(): string
    {
        return app()->getLocale();
    }
}

if (!function_exists('is_dark_mode')) {
    /**
     * Check if user prefers dark mode
     */
    function is_dark_mode(): bool
    {
        if (auth()->check()) {
            return auth()->user()->theme === 'dark';
        }

        return session('theme', 'light') === 'dark';
    }
}

if (!function_exists('get_reading_progress_color')) {
    /**
     * Get color class based on reading progress
     */
    function get_reading_progress_color(float $progress): string
    {
        if ($progress >= 100) return 'bg-green-500';
        if ($progress >= 75) return 'bg-blue-500';
        if ($progress >= 50) return 'bg-yellow-500';
        if ($progress >= 25) return 'bg-orange-500';
        return 'bg-gray-400';
    }
}

if (!function_exists('star_rating')) {
    /**
     * Generate star rating HTML
     */
    function star_rating(float $rating, int $max = 5): string
    {
        $fullStars = floor($rating);
        $halfStar = ($rating - $fullStars) >= 0.5;
        $emptyStars = $max - $fullStars - ($halfStar ? 1 : 0);

        $html = str_repeat('<span class="text-yellow-400">★</span>', $fullStars);

        if ($halfStar) {
            $html .= '<span class="text-yellow-400">☆</span>';
        }

        $html .= str_repeat('<span class="text-gray-300">☆</span>', $emptyStars);

        return $html;
    }
}
