@extends('layouts.app')
@section('title', $chronicle->title)

@section('content')
<div class="section">
    <div class="container" style="max-width: 800px;">
        @if($chronicle->cover_image)
            <img src="{{ asset('storage/' . $chronicle->cover_image) }}" alt="" style="width: 100%; height: 300px; object-fit: cover; border-radius: 20px; margin-bottom: 2rem;">
        @endif

        <h1 style="font-size: 2rem; margin-bottom: 0.5rem;">{{ $chronicle->title }}</h1>

        <div style="display: flex; gap: 1.5rem; color: #64748b; margin-bottom: 2rem; font-size: 0.9rem;">
            <span><i class="fas fa-user"></i> {{ $chronicle->author->name }}</span>
            <span><i class="fas fa-calendar"></i> {{ $chronicle->published_at?->format('d/m/Y') }}</span>
            <span><i class="fas fa-eye"></i> {{ $chronicle->views_count }} {{ __('messages.views') }}</span>
            @if($chronicle->book)
                <span><i class="fas fa-book"></i> <a href="{{ route('books.show', $chronicle->book) }}" style="color: var(--blue-roi);">{{ $chronicle->book->title }}</a></span>
            @endif
        </div>

        <div class="card" style="padding: 2rem; line-height: 1.8;">
            {!! nl2br(e($chronicle->content)) !!}
        </div>

        <!-- Comments -->
        <div style="margin-top: 2rem;">
            <h3 style="margin-bottom: 1rem;">{{ __('messages.comments') }} ({{ $chronicle->approvedComments->count() }})</h3>
            @forelse($chronicle->approvedComments as $comment)
                <div style="padding: 1rem; border-bottom: 1px solid #f1f5f9; display: flex; gap: 0.75rem;">
                    <img src="{{ $comment->user->avatar_url }}" alt="" style="width: 36px; height: 36px; border-radius: 50%;">
                    <div>
                        <div style="display: flex; gap: 0.75rem; align-items: center;">
                            <strong style="font-size: 0.9rem;">{{ $comment->user->name }}</strong>
                            <small style="color: #94a3b8;">{{ $comment->created_at->diffForHumans() }}</small>
                        </div>
                        <p style="color: #475569; margin-top: 0.25rem;">{{ $comment->content }}</p>
                    </div>
                </div>
            @empty
                <p style="color: #94a3b8; text-align: center; padding: 1rem;">{{ __('messages.no_comments') }}</p>
            @endforelse
        </div>
    </div>
</div>
@endsection
