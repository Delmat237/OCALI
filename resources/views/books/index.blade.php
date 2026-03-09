@extends('layouts.app')
@section('title', __('messages.books'))

@section('content')
<div class="section">
    <div class="container">
        <h1 class="section-title" style="margin-bottom: 2rem;">{{ __('messages.books') }}</h1>

        @if($books->count() > 0)
            <div class="grid-books">
                @foreach($books as $book)
                    <a href="{{ route('books.show', $book) }}" class="card card-elevated book-card" style="text-decoration: none;">
                        <div class="book-cover">
                            <img src="{{ $book->cover_url }}" alt="{{ $book->title }}">
                        </div>
                        <div class="book-info">
                            <h3 class="book-title">{{ $book->title }}</h3>
                            <p class="book-author">{{ $book->author->name ?? '' }}</p>
                            @if($book->average_rating > 0)
                                <div class="book-rating">
                                    @for($i = 1; $i <= 5; $i++)
                                        <i class="fas fa-star" style="color: {{ $i <= $book->average_rating ? '#FFA500' : '#d1d5db' }}"></i>
                                    @endfor
                                </div>
                            @endif
                        </div>
                    </a>
                @endforeach
            </div>

            <div style="margin-top: 2rem; display: flex; justify-content: center;">
                {{ $books->links() }}
            </div>
        @else
            <div style="text-align: center; padding: 4rem 0;">
                <i class="fas fa-book" style="font-size: 4rem; color: #d1d5db;"></i>
                <h3 style="color: #64748b; margin-top: 1rem;">{{ __('messages.no_books_found') }}</h3>
            </div>
        @endif
    </div>
</div>
@endsection
