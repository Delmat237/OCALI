@extends('layouts.app')
@section('title', $category->localized_name)

@section('content')
<div class="section">
    <div class="container">
        <div class="section-header">
            <h1 class="section-title">{{ $category->localized_name }}</h1>
            <a href="{{ route('categories') }}" class="btn btn-outline">
                <i class="fas fa-arrow-left"></i> {{ __('messages.all_categories') }}
            </a>
        </div>

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
                        </div>
                    </a>
                @endforeach
            </div>

            <div style="margin-top: 2rem; display: flex; justify-content: center;">
                {{ $books->links() }}
            </div>
        @else
            <div style="text-align: center; padding: 4rem 0;">
                <i class="fas fa-book" style="font-size: 4rem; color: #d1d5db; margin-bottom: 1rem;"></i>
                <h3 style="color: #64748b;">{{ __('messages.no_books_in_category') }}</h3>
            </div>
        @endif
    </div>
</div>
@endsection
