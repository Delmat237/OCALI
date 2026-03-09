@extends('layouts.app')
@section('title', __('messages.pending_approval'))

@section('content')
<div class="section">
    <div class="container">
        <div class="section-header">
            <h1 class="section-title">{{ __('messages.pending_approval') }}</h1>
            <a href="{{ route('admin.books.index') }}" class="btn btn-outline"><i class="fas fa-arrow-left"></i> {{ __('messages.all_books') }}</a>
        </div>

        @if($books->count() > 0)
            @foreach($books as $book)
                <div class="card card-elevated" style="display: flex; gap: 1.5rem; padding: 1.5rem; margin-bottom: 1rem;">
                    <img src="{{ $book->cover_url }}" alt="" style="width: 100px; height: 140px; object-fit: cover; border-radius: 10px;">
                    <div style="flex: 1;">
                        <h3>{{ $book->title }}</h3>
                        <p style="color: #64748b; font-size: 0.9rem;">{{ __('messages.by') }} {{ $book->author->name ?? '' }} | {{ $book->category->localized_name ?? '' }} | {{ strtoupper($book->language) }}</p>
                        <p style="color: #475569; font-size: 0.9rem; margin-top: 0.5rem;">{{ Str::limit($book->description, 200) }}</p>
                        <div style="display: flex; gap: 0.75rem; margin-top: 1rem; flex-wrap: wrap;">
                            <a href="{{ route('admin.books.preview', $book) }}" class="btn btn-secondary" style="font-size: 0.85rem;"><i class="fas fa-eye"></i> {{ __('messages.preview') }}</a>
                            <form action="{{ route('admin.books.approve', $book) }}" method="POST">@csrf
                                <button type="submit" class="btn btn-primary" style="font-size: 0.85rem;"><i class="fas fa-check"></i> {{ __('messages.approve') }}</button>
                            </form>
                            <button onclick="document.getElementById('reject-{{ $book->id }}').style.display='block'" class="btn btn-outline" style="font-size: 0.85rem; color: #ef4444; border-color: #ef4444;"><i class="fas fa-times"></i> {{ __('messages.reject') }}</button>
                        </div>
                        <div id="reject-{{ $book->id }}" style="display: none; margin-top: 0.75rem;">
                            <form action="{{ route('admin.books.reject', $book) }}" method="POST" style="display: flex; gap: 0.5rem;">
                                @csrf
                                <input type="text" name="rejection_reason" placeholder="{{ __('messages.rejection_reason') }}" required style="flex: 1; padding: 0.5rem; border: 2px solid #e2e8f0; border-radius: 8px;">
                                <button type="submit" class="btn btn-ghost" style="color: #ef4444;">{{ __('messages.confirm') }}</button>
                            </form>
                        </div>
                    </div>
                </div>
            @endforeach
            {{ $books->links() }}
        @else
            <div style="text-align: center; padding: 3rem;">
                <i class="fas fa-check-circle" style="font-size: 4rem; color: #10b981; margin-bottom: 1rem;"></i>
                <h3 style="color: #64748b;">{{ __('messages.no_pending_books') }}</h3>
            </div>
        @endif
    </div>
</div>
@endsection
