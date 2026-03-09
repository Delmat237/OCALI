@extends('layouts.app')
@section('title', __('messages.chronicles'))

@section('content')
<div class="section">
    <div class="container">
        <h1 class="section-title" style="margin-bottom: 2rem;">{{ __('messages.chronicles') }}</h1>

        @if($chronicles->count() > 0)
            <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(350px, 1fr)); gap: 1.5rem;">
                @foreach($chronicles as $chronicle)
                    <a href="{{ route('chronicles.show', $chronicle) }}" class="card card-elevated" style="text-decoration: none;">
                        @if($chronicle->cover_image)
                            <img src="{{ asset('storage/' . $chronicle->cover_image) }}" alt="" style="width: 100%; height: 200px; object-fit: cover;">
                        @endif
                        <div style="padding: 1.25rem;">
                            <h3 style="font-size: 1.1rem; margin-bottom: 0.5rem; color: #1e293b;">{{ $chronicle->title }}</h3>
                            <p style="color: #64748b; font-size: 0.9rem; margin-bottom: 0.75rem;">{{ $chronicle->excerpt }}</p>
                            <div style="display: flex; justify-content: space-between; align-items: center; font-size: 0.8rem; color: #94a3b8;">
                                <span><i class="fas fa-user"></i> {{ $chronicle->author->name }}</span>
                                <span>{{ $chronicle->published_at?->diffForHumans() }}</span>
                            </div>
                        </div>
                    </a>
                @endforeach
            </div>
            <div style="margin-top: 2rem; display: flex; justify-content: center;">{{ $chronicles->links() }}</div>
        @else
            <div style="text-align: center; padding: 4rem 0;">
                <i class="fas fa-pen-fancy" style="font-size: 4rem; color: #d1d5db;"></i>
                <h3 style="color: #64748b; margin-top: 1rem;">{{ __('messages.no_chronicles') }}</h3>
            </div>
        @endif
    </div>
</div>
@endsection
