@extends('layouts.app')
@section('title', __('messages.my_books'))

@section('content')
<div class="section">
    <div class="container">
        <div class="section-header">
            <h1 class="section-title">{{ __('messages.my_books') }}</h1>
            <a href="{{ route('author.books.create') }}" class="btn btn-primary"><i class="fas fa-plus"></i> {{ __('messages.publish_book') }}</a>
        </div>

        @if($books->count() > 0)
            <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); gap: 1.5rem;">
                @foreach($books as $book)
                    <div class="card card-elevated" style="display: flex; gap: 1rem; padding: 1.25rem;">
                        <img src="{{ $book->cover_url }}" alt="" style="width: 80px; height: 115px; object-fit: cover; border-radius: 10px;">
                        <div style="flex: 1;">
                            <h3 style="font-size: 1rem; margin-bottom: 0.25rem;">{{ $book->title }}</h3>
                            <span style="font-size: 0.75rem; padding: 0.15rem 0.5rem; border-radius: 10px; background: {{ $book->status === 'approved' ? 'rgba(16,185,129,0.1)' : ($book->status === 'pending' ? 'rgba(245,158,11,0.1)' : 'rgba(239,68,68,0.1)') }}; color: {{ $book->status === 'approved' ? '#059669' : ($book->status === 'pending' ? '#d97706' : '#dc2626') }};">{{ $book->status_label }}</span>
                            <div style="display: flex; gap: 1rem; color: #64748b; font-size: 0.8rem; margin-top: 0.5rem;">
                                <span><i class="fas fa-eye"></i> {{ $book->views_count }}</span>
                                <span><i class="fas fa-book-reader"></i> {{ $book->reads_count }}</span>
                            </div>
                            <div style="display: flex; gap: 0.5rem; margin-top: 0.75rem;">
                                <a href="{{ route('author.books.edit', $book) }}" class="btn btn-ghost" style="padding: 0.3rem 0.6rem; font-size: 0.8rem;"><i class="fas fa-edit"></i></a>
                                <form action="{{ route('author.books.destroy', $book) }}" method="POST" onsubmit="return confirm('{{ __('messages.confirm_delete') }}')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-ghost" style="padding: 0.3rem 0.6rem; font-size: 0.8rem; color: #ef4444;"><i class="fas fa-trash"></i></button>
                                </form>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            {{ $books->links() }}
        @else
            <div style="text-align: center; padding: 4rem 0;">
                <i class="fas fa-book" style="font-size: 4rem; color: #d1d5db; margin-bottom: 1rem;"></i>
                <h3 style="color: #64748b;">{{ __('messages.no_books_yet') }}</h3>
                <a href="{{ route('author.books.create') }}" class="btn btn-primary" style="margin-top: 1rem;">{{ __('messages.publish_first_book') }}</a>
            </div>
        @endif
    </div>
</div>
@endsection
