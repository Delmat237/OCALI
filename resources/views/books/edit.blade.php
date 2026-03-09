@extends('layouts.app')
@section('title', __('messages.edit_book'))

@section('content')
<div class="section">
    <div class="container" style="max-width: 700px;">
        <h1 class="section-title" style="margin-bottom: 2rem;">{{ __('messages.edit_book') }}</h1>

        <div class="card card-elevated" style="padding: 2rem;">
            <form action="{{ route('author.books.update', $book) }}" method="POST" enctype="multipart/form-data">
                @csrf @method('PUT')
                <div style="margin-bottom: 1rem;">
                    <label style="display: block; margin-bottom: 0.5rem; font-weight: 600;">{{ __('messages.title') }} *</label>
                    <input type="text" name="title" value="{{ old('title', $book->title) }}" required
                        style="width: 100%; padding: 0.75rem; border: 2px solid #e2e8f0; border-radius: 12px;">
                </div>
                <div style="margin-bottom: 1rem;">
                    <label style="display: block; margin-bottom: 0.5rem; font-weight: 600;">{{ __('messages.description') }} *</label>
                    <textarea name="description" rows="5" required style="width: 100%; padding: 0.75rem; border: 2px solid #e2e8f0; border-radius: 12px; resize: vertical;">{{ old('description', $book->description) }}</textarea>
                </div>
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin-bottom: 1rem;">
                    <div>
                        <label style="display: block; margin-bottom: 0.5rem; font-weight: 600;">{{ __('messages.category') }} *</label>
                        <select name="category_id" required style="width: 100%; padding: 0.75rem; border: 2px solid #e2e8f0; border-radius: 12px;">
                            @foreach($categories as $cat)
                                <option value="{{ $cat->id }}" {{ old('category_id', $book->category_id) == $cat->id ? 'selected' : '' }}>{{ $cat->localized_name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label style="display: block; margin-bottom: 0.5rem; font-weight: 600;">{{ __('messages.language') }} *</label>
                        <select name="language" required style="width: 100%; padding: 0.75rem; border: 2px solid #e2e8f0; border-radius: 12px;">
                            <option value="fr" {{ old('language', $book->language) === 'fr' ? 'selected' : '' }}>Français</option>
                            <option value="en" {{ old('language', $book->language) === 'en' ? 'selected' : '' }}>English</option>
                        </select>
                    </div>
                </div>
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin-bottom: 1rem;">
                    <div>
                        <label style="display: block; margin-bottom: 0.5rem; font-weight: 600;">{{ __('messages.publisher') ?? 'Maison d\'édition' }}</label>
                        <input type="text" name="publisher" value="{{ old('publisher', $book->publisher) }}"
                            style="width: 100%; padding: 0.75rem; border: 2px solid #e2e8f0; border-radius: 12px;">
                    </div>
                    <div>
                        <label style="display: block; margin-bottom: 0.5rem; font-weight: 600;">{{ __('messages.keywords') }}</label>
                        <input type="text" name="keywords" value="{{ old('keywords', $book->keywords ? implode(', ', $book->keywords) : '') }}"
                            style="width: 100%; padding: 0.75rem; border: 2px solid #e2e8f0; border-radius: 12px;">
                    </div>
                </div>
                <div style="margin-bottom: 1rem;">
                    <label style="display: block; margin-bottom: 0.5rem; font-weight: 600;">{{ __('messages.cover_image') }}</label>
                    <div style="display: flex; align-items: center; gap: 1rem;">
                        <img src="{{ $book->cover_url }}" alt="" style="width: 60px; height: 85px; object-fit: cover; border-radius: 8px;">
                        <input type="file" name="cover_image" accept="image/*"
                            style="flex: 1; padding: 0.5rem; border: 2px solid #e2e8f0; border-radius: 12px;">
                    </div>
                </div>
                <div style="margin-bottom: 1.5rem;">
                    <label style="display: flex; align-items: center; gap: 0.5rem; cursor: pointer;">
                        <input type="checkbox" name="is_premium" value="1" {{ old('is_premium', $book->is_premium) ? 'checked' : '' }}>
                        <span style="font-weight: 600;">{{ __('messages.premium_book') }}</span>
                    </label>
                </div>
                <button type="submit" class="btn btn-primary" style="width: 100%;">{{ __('messages.save_changes') }}</button>
            </form>
        </div>
    </div>
</div>
@endsection
