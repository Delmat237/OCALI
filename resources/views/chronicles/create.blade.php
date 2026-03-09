@extends('layouts.app')
@section('title', __('messages.new_chronicle'))

@section('content')
<div class="section">
    <div class="container" style="max-width: 700px;">
        <h1 class="section-title" style="margin-bottom: 2rem;">{{ __('messages.new_chronicle') }}</h1>
        <div class="card card-elevated" style="padding: 2rem;">
            <form action="{{ route('author.chronicles.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div style="margin-bottom: 1rem;">
                    <label style="display: block; margin-bottom: 0.5rem; font-weight: 600;">{{ __('messages.title') }} *</label>
                    <input type="text" name="title" value="{{ old('title') }}" required style="width: 100%; padding: 0.75rem; border: 2px solid #e2e8f0; border-radius: 12px;">
                </div>
                <div style="margin-bottom: 1rem;">
                    <label style="display: block; margin-bottom: 0.5rem; font-weight: 600;">{{ __('messages.related_book') }}</label>
                    <select name="book_id" style="width: 100%; padding: 0.75rem; border: 2px solid #e2e8f0; border-radius: 12px;">
                        <option value="">{{ __('messages.none') }}</option>
                        @foreach($books as $book)
                            <option value="{{ $book->id }}">{{ $book->title }}</option>
                        @endforeach
                    </select>
                </div>
                <div style="margin-bottom: 1rem;">
                    <label style="display: block; margin-bottom: 0.5rem; font-weight: 600;">{{ __('messages.content') }} *</label>
                    <textarea name="content" rows="15" required style="width: 100%; padding: 0.75rem; border: 2px solid #e2e8f0; border-radius: 12px; resize: vertical;">{{ old('content') }}</textarea>
                </div>
                <div style="margin-bottom: 1.5rem;">
                    <label style="display: block; margin-bottom: 0.5rem; font-weight: 600;">{{ __('messages.cover_image') }}</label>
                    <input type="file" name="cover_image" accept="image/*" style="width: 100%; padding: 0.5rem; border: 2px solid #e2e8f0; border-radius: 12px;">
                </div>
                <button type="submit" class="btn btn-primary" style="width: 100%;">{{ __('messages.publish_chronicle') }}</button>
            </form>
        </div>
    </div>
</div>
@endsection
