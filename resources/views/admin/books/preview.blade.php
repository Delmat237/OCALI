@extends('layouts.app')
@section('title', __('messages.preview') . ' - ' . $book->title)

@push('styles')
<style>
    .preview-container {
        max-width: 1000px;
        margin: 0 auto;
        padding: 2rem;
    }
    .preview-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        gap: 1.5rem;
        margin-bottom: 1.5rem;
        padding-bottom: 1.5rem;
        border-bottom: 2px solid #e2e8f0;
    }
    .dark .preview-header {
        border-bottom-color: #334155;
    }
    .preview-book-info {
        display: flex;
        gap: 1.5rem;
        flex: 1;
    }
    .preview-cover {
        width: 120px;
        height: 170px;
        object-fit: cover;
        border-radius: 12px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.15);
    }
    .preview-meta {
        flex: 1;
    }
    .preview-meta h1 {
        font-size: 1.4rem;
        margin-bottom: 0.5rem;
    }
    .preview-meta p {
        color: #64748b;
        font-size: 0.9rem;
        margin-bottom: 0.25rem;
    }
    .preview-badge {
        display: inline-block;
        padding: 0.25rem 0.75rem;
        border-radius: 20px;
        font-size: 0.8rem;
        font-weight: 600;
        margin-top: 0.5rem;
    }
    .badge-pending {
        background: #fef3c7;
        color: #92400e;
    }
    .badge-approved {
        background: #dcfce7;
        color: #166534;
    }
    .preview-actions {
        display: flex;
        flex-direction: column;
        gap: 0.5rem;
        min-width: 160px;
    }
    .preview-description {
        background: #f8fafc;
        border-radius: 12px;
        padding: 1.25rem;
        margin-bottom: 1.5rem;
        color: #475569;
        line-height: 1.6;
    }
    .dark .preview-description {
        background: #1e293b;
        color: #94a3b8;
    }
    .pdf-viewer-admin {
        width: 100%;
        height: 80vh;
        border: none;
        border-radius: 12px;
        box-shadow: 0 10px 40px rgba(0,0,0,0.1);
    }
    .preview-file-info {
        display: flex;
        gap: 1.5rem;
        margin-bottom: 1rem;
        color: #64748b;
        font-size: 0.85rem;
    }
    .preview-file-info span {
        display: flex;
        align-items: center;
        gap: 0.4rem;
    }
    .reject-form {
        display: none;
        margin-top: 0.5rem;
    }
    .reject-form input {
        width: 100%;
        padding: 0.5rem 0.75rem;
        border: 2px solid #e2e8f0;
        border-radius: 8px;
        margin-bottom: 0.5rem;
        font-size: 0.9rem;
    }
    .dark .reject-form input {
        background: #1e293b;
        border-color: #334155;
        color: #e2e8f0;
    }
</style>
@endpush

@section('content')
<div class="section" style="padding-top: 1rem;">
    <div class="preview-container">
        {{-- Header with book info and actions --}}
        <div class="preview-header">
            <div class="preview-book-info">
                @if($book->cover_image)
                    <img src="{{ $book->cover_url }}" alt="{{ $book->title }}" class="preview-cover">
                @else
                    <div class="preview-cover" style="background: linear-gradient(135deg, var(--blue-roi), var(--orange-fluo)); display: flex; align-items: center; justify-content: center;">
                        <i class="fas fa-book" style="font-size: 2.5rem; color: white;"></i>
                    </div>
                @endif
                <div class="preview-meta">
                    <h1>{{ $book->title }}</h1>
                    <p><i class="fas fa-user"></i> {{ $book->author->name ?? __('messages.unknown') }}</p>
                    <p><i class="fas fa-folder"></i> {{ $book->category->localized_name ?? '-' }}</p>
                    <p><i class="fas fa-file-alt"></i> {{ $book->pages_count ?? '?' }} {{ __('messages.pages') }} &bull; {{ strtoupper($book->language ?? 'fr') }} &bull; {{ ucfirst($book->type ?? 'book') }}</p>
                    <p><i class="fas fa-calendar"></i> {{ __('messages.submitted') }}: {{ $book->created_at->format('d/m/Y H:i') }}</p>
                    <span class="preview-badge {{ $book->status === 'pending' ? 'badge-pending' : 'badge-approved' }}">
                        {{ $book->status === 'pending' ? __('messages.status_pending') : __('messages.status_approved') }}
                    </span>
                </div>
            </div>

            @if($book->status === 'pending')
            <div class="preview-actions">
                <form action="{{ route('admin.books.approve', $book) }}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-primary" style="width: 100%;">
                        <i class="fas fa-check"></i> {{ __('messages.approve') }}
                    </button>
                </form>
                <button onclick="document.getElementById('rejectForm').style.display = document.getElementById('rejectForm').style.display === 'none' ? 'block' : 'none'" class="btn btn-outline" style="color: #ef4444; border-color: #ef4444;">
                    <i class="fas fa-times"></i> {{ __('messages.reject') }}
                </button>
                <div id="rejectForm" class="reject-form">
                    <form action="{{ route('admin.books.reject', $book) }}" method="POST">
                        @csrf
                        <input type="text" name="rejection_reason" placeholder="{{ __('messages.rejection_reason') }}" required>
                        <button type="submit" class="btn btn-ghost" style="width: 100%; color: #ef4444;">{{ __('messages.confirm') }}</button>
                    </form>
                </div>
                <a href="{{ route('admin.books.pending') }}" class="btn btn-ghost" style="margin-top: 0.25rem;">
                    <i class="fas fa-arrow-left"></i> {{ __('messages.back') }}
                </a>
            </div>
            @else
            <div class="preview-actions">
                <a href="{{ route('admin.books.index') }}" class="btn btn-ghost">
                    <i class="fas fa-arrow-left"></i> {{ __('messages.back') }}
                </a>
            </div>
            @endif
        </div>

        {{-- Description --}}
        @if($book->description)
        <div class="preview-description">
            <strong>{{ __('messages.description') }}:</strong><br>
            {{ $book->description }}
        </div>
        @endif

        {{-- PDF Viewer --}}
        @if($book->file_path)
            <div class="preview-file-info">
                <span><i class="fas fa-file-pdf"></i> {{ basename($book->file_path) }}</span>
                <span><i class="fas fa-database"></i> {{ $book->pages_count ?? '?' }} {{ __('messages.pages') }}</span>
            </div>
            <iframe src="{{ $book->file_url }}" class="pdf-viewer-admin"></iframe>
        @else
            <div style="text-align: center; padding: 4rem; background: #f8fafc; border-radius: 16px;">
                <i class="fas fa-file-circle-exclamation" style="font-size: 4rem; color: #94a3b8; margin-bottom: 1rem;"></i>
                <h3 style="color: #64748b;">{{ __('messages.no_file_uploaded') }}</h3>
            </div>
        @endif
    </div>
</div>
@endsection
