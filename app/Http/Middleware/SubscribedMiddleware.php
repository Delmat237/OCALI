<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SubscribedMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!auth()->check() || !auth()->user()->hasActiveSubscription()) {
            return redirect()->route('pricing')
                ->with('warning', __('messages.subscription_required'));
        }

        return $next($request);
    }
}
