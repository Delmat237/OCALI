@extends('layouts.app')
@section('title', __('messages.my_library'))

@section('content')
<div class="section">
    <div class="container">
        <div class="section-header">
            <h1 class="section-title">{{ __('messages.my_library') }}</h1>
            <a href="{{ route('library.history') }}" class="btn btn-ghost">{{ __('messages.reading_history') }}</a>
        </div>

        <!-- Currently Reading -->
        @if($currentlyReading->count() > 0)
            <h2 style="font-size: 1.3rem; margin-bottom: 1rem; color: var(--blue-roi);">{{ __('messages.currently_reading') }}</h2>
            <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); gap: 1.5rem; margin-bottom: 3rem;">
                @foreach($currentlyReading as $ub)
                    <div class="card card-elevated" style="display: flex; gap: 1rem; padding: 1.25rem;">
                        <img src="{{ $ub->book->cover_url }}" alt="" style="width: 80px; height: 115px; object-fit: cover; border-radius: 10px;">
                        <div style="flex: 1;">
                            <h3 style="font-size: 1rem; margin-bottom: 0.25rem;">{{ $ub->book->title }}</h3>
                            <p style="font-size: 0.85rem; color: #64748b; margin-bottom: 0.5rem;">{{ $ub->book->author->name ?? '' }}</p>
                            <div style="background: #e2e8f0; border-radius: 10px; height: 6px; margin-bottom: 0.25rem;">
                                <div style="background: var(--gradient-primary); height: 100%; border-radius: 10px; width: {{ $ub->progress_percent }}%;"></div>
                            </div>
                            <div style="display: flex; justify-content: space-between; align-items: center;">
                                <small style="color: #94a3b8;">{{ number_format($ub->progress_percent, 0) }}% - {{ $ub->formatted_reading_time }}</small>
                                <a href="{{ route('books.read', $ub->book) }}" class="btn btn-primary" style="padding: 0.4rem 0.75rem; font-size: 0.8rem;">{{ __('messages.read') }}</a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif

        <!-- Completed -->
        @if($completed->count() > 0)
            <h2 style="font-size: 1.3rem; margin-bottom: 1rem; color: #10b981;">{{ __('messages.completed') }}</h2>
            <div class="grid-books">
                @foreach($completed as $ub)
                    <a href="{{ route('books.show', $ub->book) }}" class="card card-elevated book-card" style="text-decoration: none;">
                        <div class="book-cover" style="position: relative;">
                            <img src="{{ $ub->book->cover_url }}" alt="">
                            <div style="position: absolute; top: 8px; right: 8px; background: #10b981; color: white; width: 28px; height: 28px; border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                                <i class="fas fa-check" style="font-size: 0.75rem;"></i>
                            </div>
                        </div>
                        <div class="book-info">
                            <h3 class="book-title">{{ $ub->book->title }}</h3>
                            <p class="book-author">{{ $ub->book->author->name ?? '' }}</p>
                        </div>
                    </a>
                @endforeach
            </div>
        @endif

        @if($currentlyReading->count() === 0 && $completed->count() === 0)
            <div style="text-align: center; padding: 4rem 0;">
                <i class="fas fa-book-open" style="font-size: 4rem; color: #d1d5db; margin-bottom: 1rem;"></i>
                <h3 style="color: #64748b;">{{ __('messages.library_empty') }}</h3>
                <p style="color: #94a3b8; margin-bottom: 1.5rem;">{{ __('messages.library_empty_text') }}</p>
                <a href="{{ route('explore') }}" class="btn btn-primary">{{ __('messages.explore_books') }}</a>
            </div>
        @endif
    </div>
</div>
@endsection
