@extends('layouts.app')
@section('title', __('messages.manage_books'))

@section('content')
<div class="section">
    <div class="container">
        <div class="section-header">
            <h1 class="section-title">{{ __('messages.manage_books') }}</h1>
            <a href="{{ route('admin.books.pending') }}" class="btn btn-primary">{{ __('messages.pending_approval') }}</a>
        </div>

        <div class="card card-elevated" style="overflow-x: auto;">
            <table style="width: 100%; border-collapse: collapse;">
                <thead>
                    <tr style="border-bottom: 2px solid #e2e8f0;">
                        <th style="padding: 0.75rem 1rem; text-align: left;">{{ __('messages.book') }}</th>
                        <th style="padding: 0.75rem 1rem; text-align: left;">{{ __('messages.author') }}</th>
                        <th style="padding: 0.75rem 1rem; text-align: center;">{{ __('messages.status') }}</th>
                        <th style="padding: 0.75rem 1rem; text-align: center;">{{ __('messages.reads') }}</th>
                        <th style="padding: 0.75rem 1rem; text-align: center;">{{ __('messages.date') }}</th>
                        <th style="padding: 0.75rem 1rem; text-align: center;">{{ __('messages.actions') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($books as $book)
                        <tr style="border-bottom: 1px solid #f1f5f9;">
                            <td style="padding: 0.75rem 1rem;">
                                <div style="display: flex; align-items: center; gap: 0.75rem;">
                                    <img src="{{ $book->cover_url }}" alt="" style="width: 36px; height: 50px; object-fit: cover; border-radius: 4px;">
                                    <span>{{ $book->title }}</span>
                                </div>
                            </td>
                            <td style="padding: 0.75rem 1rem;">{{ $book->author->name ?? '-' }}</td>
                            <td style="padding: 0.75rem 1rem; text-align: center;">
                                <span style="padding: 0.2rem 0.5rem; border-radius: 10px; font-size: 0.8rem; background: {{ $book->status === 'approved' ? 'rgba(16,185,129,0.1)' : ($book->status === 'pending' ? 'rgba(245,158,11,0.1)' : 'rgba(239,68,68,0.1)') }}; color: {{ $book->status === 'approved' ? '#059669' : ($book->status === 'pending' ? '#d97706' : '#dc2626') }};">{{ $book->status_label }}</span>
                            </td>
                            <td style="padding: 0.75rem 1rem; text-align: center;">{{ $book->reads_count }}</td>
                            <td style="padding: 0.75rem 1rem; text-align: center;">{{ $book->created_at->format('d/m/Y') }}</td>
                            <td style="padding: 0.75rem 1rem; text-align: center;">
                                <div style="display: flex; gap: 0.5rem; justify-content: center;">
                                    @if($book->status === 'pending')
                                        <form action="{{ route('admin.books.approve', $book) }}" method="POST" style="display: inline;">@csrf <button class="btn btn-ghost" style="padding: 0.3rem 0.6rem; color: #10b981;"><i class="fas fa-check"></i></button></form>
                                    @endif
                                    <form action="{{ route('admin.books.welcome', $book) }}" method="POST" style="display: inline;">@csrf @method('PUT') <button class="btn btn-ghost" style="padding: 0.3rem 0.6rem;" title="{{ __('messages.set_welcome_book') }}"><i class="fas fa-gift"></i></button></form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        {{ $books->links() }}
    </div>
</div>
@endsection
