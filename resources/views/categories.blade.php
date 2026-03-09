@extends('layouts.app')
@section('title', __('messages.categories'))

@section('content')
<div class="section">
    <div class="container">
        <h1 class="section-title" style="margin-bottom: 2rem;">{{ __('messages.categories') }}</h1>

        <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(250px, 1fr)); gap: 1.5rem;">
            @foreach($categories as $category)
                <a href="{{ route('category.show', $category) }}" class="card card-elevated" style="text-decoration: none; padding: 2rem; text-align: center;">
                    @if($category->icon)
                        <div style="font-size: 2.5rem; margin-bottom: 1rem; color: {{ $category->color ?? 'var(--blue-roi)' }};">
                            <i class="fas {{ $category->icon }}"></i>
                        </div>
                    @endif
                    <h3 style="font-size: 1.2rem; color: #1e293b; margin-bottom: 0.5rem;">{{ $category->localized_name }}</h3>
                    <p style="color: #64748b; font-size: 0.9rem;">{{ $category->books_count }} {{ __('messages.books') }}</p>
                </a>
            @endforeach
        </div>
    </div>
</div>
@endsection
