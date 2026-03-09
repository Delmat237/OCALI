@extends('layouts.app')
@section('title', __('messages.statistics'))

@section('content')
<div class="section">
    <div class="container">
        <h1 class="section-title" style="margin-bottom: 2rem;">{{ __('messages.statistics') }}</h1>

        <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 1.5rem; margin-bottom: 2rem;">
            <div class="card card-elevated" style="padding: 1.5rem; text-align: center;">
                <div style="font-size: 2rem; font-weight: 800; color: var(--blue-roi);">{{ number_format($totalReads) }}</div>
                <div style="color: #64748b;">{{ __('messages.total_reads') }}</div>
            </div>
            <div class="card card-elevated" style="padding: 1.5rem; text-align: center;">
                <div style="font-size: 2rem; font-weight: 800; color: var(--orange-fluo);">{{ number_format($totalViews) }}</div>
                <div style="color: #64748b;">{{ __('messages.total_views') }}</div>
            </div>
            <div class="card card-elevated" style="padding: 1.5rem; text-align: center;">
                <div style="font-size: 2rem; font-weight: 800; color: #10b981;">{{ $averageRating ? number_format($averageRating, 1) : '-' }}</div>
                <div style="color: #64748b;">{{ __('messages.average_rating') }}</div>
            </div>
        </div>

        <div class="card card-elevated" style="padding: 1.5rem;">
            <h3 style="margin-bottom: 1rem;">{{ __('messages.books_performance') }}</h3>
            @foreach($readingStats as $stat)
                <div style="display: flex; align-items: center; gap: 1rem; padding: 0.75rem 0; border-bottom: 1px solid #f1f5f9;">
                    <img src="{{ $stat['book']->cover_url }}" alt="" style="width: 40px; height: 55px; object-fit: cover; border-radius: 6px;">
                    <div style="flex: 1;">
                        <h4 style="font-size: 0.9rem;">{{ $stat['book']->title }}</h4>
                        <div style="display: flex; gap: 1rem; color: #64748b; font-size: 0.8rem;">
                            <span>{{ $stat['book']->reads_count }} {{ __('messages.reads') }}</span>
                            <span>{{ $stat['book']->views_count }} {{ __('messages.views') }}</span>
                            <span>{{ $stat['book']->reviews_count }} {{ __('messages.reviews') }}</span>
                        </div>
                    </div>
                    <div style="width: 120px;">
                        <div style="background: #e2e8f0; border-radius: 10px; height: 8px;">
                            <div style="background: var(--gradient-primary); height: 100%; border-radius: 10px; width: {{ min(100, $stat['percentage']) }}%;"></div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>
@endsection
