@extends('layouts.app')
@section('title', __('messages.dashboard'))

@section('content')
<div class="section">
    <div class="container">
        <h1 class="section-title" style="margin-bottom: 2rem;">{{ __('messages.welcome_back') }}, {{ $user->name }}!</h1>

        <!-- Stats -->
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1.5rem; margin-bottom: 2rem;">
            <div class="card card-elevated" style="padding: 1.5rem; text-align: center;">
                <div style="font-size: 2rem; font-weight: 800; color: var(--orange-fluo);">{{ $stats['books_read'] }}</div>
                <div style="color: #64748b;">{{ __('messages.books_read') }}</div>
            </div>
            <div class="card card-elevated" style="padding: 1.5rem; text-align: center;">
                <div style="font-size: 2rem; font-weight: 800; color: var(--blue-roi);">{{ format_reading_time($stats['reading_time']) }}</div>
                <div style="color: #64748b;">{{ __('messages.reading_time') }}</div>
            </div>
            <div class="card card-elevated" style="padding: 1.5rem; text-align: center;">
                <div style="font-size: 2rem; font-weight: 800; color: #10b981;">{{ $stats['books_in_progress'] }}</div>
                <div style="color: #64748b;">{{ __('messages.in_progress') }}</div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="card card-elevated" style="padding: 1.5rem; margin-bottom: 2rem;">
            <h3 style="margin-bottom: 1rem;">{{ __('messages.quick_actions') }}</h3>
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem;">
                <a href="{{ route('profile') }}" class="btn btn-outline" style="width: 100%;">
                    <i class="fas fa-user"></i> {{ __('messages.profile') }}
                </a>
                <a href="{{ route('library') }}" class="btn btn-outline" style="width: 100%;">
                    <i class="fas fa-book"></i> {{ __('messages.my_library') }}
                </a>
                <a href="{{ route('library.history') }}" class="btn btn-outline" style="width: 100%;">
                    <i class="fas fa-history"></i> {{ __('messages.history') }}
                </a>
                <a href="{{ route('notifications') }}" class="btn btn-outline" style="width: 100%;">
                    <i class="fas fa-bell"></i> {{ __('messages.notifications') }}
                </a>
            </div>
        </div>

        <!-- Subscription Status -->
        @if($subscription)
            <div class="card card-elevated" style="padding: 1.5rem; margin-bottom: 2rem; background: linear-gradient(135deg, rgba(65,105,225,0.05), rgba(255,165,0,0.05));">
                <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 1rem;">
                    <div>
                        <h3>{{ $subscription->plan->localized_name }}</h3>
                        <p style="color: #64748b;">{{ __('messages.expires') }}: {{ $subscription->ends_at->format('d/m/Y') }} ({{ $subscription->days_remaining }} {{ __('messages.days_remaining') }})</p>
                        <p style="color: #64748b;">{{ __('messages.books_remaining') }}: {{ $subscription->books_remaining }}</p>
                    </div>
                    <a href="{{ route('pricing') }}" class="btn btn-outline">{{ __('messages.renew') }}</a>
                </div>
            </div>
        @else
            <div class="card card-elevated" style="padding: 2rem; margin-bottom: 2rem; text-align: center; background: linear-gradient(135deg, rgba(255,165,0,0.05), rgba(65,105,225,0.05));">
                <i class="fas fa-crown" style="font-size: 2rem; color: var(--orange-fluo); margin-bottom: 0.75rem;"></i>
                <h3>{{ __('messages.no_subscription') }}</h3>
                <p style="color: #64748b; margin-bottom: 1rem;">{{ __('messages.subscribe_to_read') }}</p>
                <a href="{{ route('pricing') }}" class="btn btn-primary">{{ __('messages.see_plans') }}</a>
            </div>
        @endif

        <!-- Currently Reading -->
        @if($currentlyReading->count() > 0)
            <div style="margin-bottom: 2rem;">
                <div class="section-header">
                    <h2 class="section-title">{{ __('messages.currently_reading') }}</h2>
                    <a href="{{ route('library') }}" class="btn btn-ghost">{{ __('messages.see_all') }}</a>
                </div>
                <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); gap: 1.5rem;">
                    @foreach($currentlyReading as $ub)
                        <div class="card card-elevated" style="display: flex; gap: 1rem; padding: 1rem;">
                            <img src="{{ $ub->book->cover_url }}" alt="" style="width: 70px; height: 100px; object-fit: cover; border-radius: 8px;">
                            <div style="flex: 1;">
                                <h4 style="font-size: 0.95rem; margin-bottom: 0.25rem;">{{ $ub->book->title }}</h4>
                                <p style="font-size: 0.8rem; color: #64748b; margin-bottom: 0.5rem;">{{ $ub->book->author->name ?? '' }}</p>
                                <div style="background: #e2e8f0; border-radius: 10px; height: 6px; margin-bottom: 0.25rem;">
                                    <div style="background: var(--gradient-primary); height: 100%; border-radius: 10px; width: {{ $ub->progress_percent }}%;"></div>
                                </div>
                                <small style="color: #94a3b8;">{{ number_format($ub->progress_percent, 0) }}%</small>
                                <a href="{{ route('books.read', $ub->book) }}" class="btn btn-primary" style="margin-top: 0.5rem; padding: 0.4rem 0.75rem; font-size: 0.8rem;">{{ __('messages.continue') }}</a>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        <!-- Recommendations -->
        @if($recommendedBooks->count() > 0)
            <div>
                <h2 class="section-title" style="margin-bottom: 1.5rem;">{{ __('messages.recommended') }}</h2>
                <div class="grid-books">
                    @foreach($recommendedBooks as $book)
                        <a href="{{ route('books.show', $book) }}" class="card card-elevated book-card" style="text-decoration: none;">
                            <div class="book-cover">
                                <img src="{{ $book->cover_url }}" alt="{{ $book->title }}">
                            </div>
                            <div class="book-info">
                                <h3 class="book-title">{{ $book->title }}</h3>
                                <p class="book-author">{{ $book->author->name ?? '' }}</p>
                            </div>
                        </a>
                    @endforeach
                </div>
            </div>
        @endif
    </div>
</div>
@endsection
