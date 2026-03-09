<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SetLocale
{
    public function handle(Request $request, Closure $next): Response
    {
        // Priority: URL param > User preference > Session > Browser > Default
        $locale = $request->get('lang')
            ?? (auth()->check() ? auth()->user()->locale : null)
            ?? session('locale')
            ?? substr($request->server('HTTP_ACCEPT_LANGUAGE', 'fr'), 0, 2);

        // Ensure valid locale
        if (!in_array($locale, ['en', 'fr'])) {
            $locale = config('app.locale', 'fr');
        }

        app()->setLocale($locale);
        session(['locale' => $locale]);

        return $next($request);
    }
}
