@extends('layouts.app')
@section('title', __('messages.explore'))

@section('content')
<div class="section">
    <div class="container">
        <div class="section-header">
            <h1 class="section-title">{{ __('messages.explore') }}</h1>
        </div>

        <!-- Search & Filters -->
        <form method="GET" action="{{ route('explore') }}" style="margin-bottom: 2rem;">
            <div style="display: flex; gap: 1rem; flex-wrap: wrap; align-items: center;">
                <div style="flex: 1; min-width: 250px;">
                    <input type="text" name="q" value="{{ request('q') }}" placeholder="{{ __('messages.search_books') }}"
                        style="width: 100%; padding: 0.75rem 1.25rem; border-radius: 50px; border: 2px solid #e2e8f0; font-size: 1rem; font-family: 'Roboto', sans-serif; outline: none; transition: border-color 0.3s;">
                </div>
                <select name="category" onchange="this.form.submit()" style="padding: 0.75rem 1.25rem; border-radius: 50px; border: 2px solid #e2e8f0; font-family: 'Roboto', sans-serif;">
                    <option value="">{{ __('messages.all_categories') }}</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat->slug }}" {{ request('category') == $cat->slug ? 'selected' : '' }}>{{ $cat->localized_name }}</option>
                    @endforeach
                </select>
                <select name="type" onchange="this.form.submit()" style="padding: 0.75rem 1.25rem; border-radius: 50px; border: 2px solid #e2e8f0; font-family: 'Roboto', sans-serif;">
                    <option value="">{{ __('messages.all_types') }}</option>
                    <option value="book" {{ request('type') == 'book' ? 'selected' : '' }}>{{ __('messages.type_book') }}</option>
                    <option value="scientific_review" {{ request('type') == 'scientific_review' ? 'selected' : '' }}>{{ __('messages.type_scientific_review') }}</option>
                </select>
                <select name="sort" onchange="this.form.submit()" style="padding: 0.75rem 1.25rem; border-radius: 50px; border: 2px solid #e2e8f0; font-family: 'Roboto', sans-serif;">
                    <option value="latest" {{ request('sort') == 'latest' ? 'selected' : '' }}>{{ __('messages.sort_latest') }}</option>
                    <option value="popular" {{ request('sort') == 'popular' ? 'selected' : '' }}>{{ __('messages.sort_popular') }}</option>
                    <option value="rating" {{ request('sort') == 'rating' ? 'selected' : '' }}>{{ __('messages.sort_rating') }}</option>
                    <option value="title" {{ request('sort') == 'title' ? 'selected' : '' }}>{{ __('messages.sort_title') }}</option>
                </select>
                <!-- Optionnel: masquer le bouton submit si l'utilisateur ne tape pas dans l'input, mais pour la barre de recherche textuelle, il peut être utile. On peut le laisser pour "Enter" sur la barre de recherche. -->
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-search"></i> {{ __('messages.search') }}
                </button>
            </div>
        </form>

        <!-- Results -->
        @if($books->count() > 0)
            <div class="grid-books">
                @foreach($books as $book)
                    <div class="card card-elevated book-card" style="text-decoration: none; display: flex; flex-direction: column; overflow: hidden; border-radius: 12px; padding: 0;">
                        <a href="{{ route('books.show', $book) }}" style="display: block; text-decoration: none; color: inherit; flex-grow: 1;">
                            <div class="book-cover" style="position: relative;">
                                <img src="{{ $book->cover_url }}" alt="{{ $book->title }}" style="width: 100%; aspect-ratio: 2/3; object-fit: cover; border-radius: 12px 12px 0 0;">
                                @if($book->is_premium)
                                    <span style="position: absolute; top: 10px; right: 10px; background: linear-gradient(135deg, #f59e0b, #d97706); color: white; padding: 0.25rem 0.5rem; border-radius: 20px; font-size: 0.7rem; font-weight: bold; box-shadow: 0 2px 4px rgba(0,0,0,0.2); z-index: 10;">Premium</span>
                                @endif
                                <span style="position: absolute; bottom: 10px; left: 10px; background: rgba(0,0,0,0.6); color: white; padding: 0.2rem 0.5rem; border-radius: 4px; font-size: 0.7rem; z-index: 10;">{{ $book->category->localized_name ?? '' }}</span>
                            </div>
                            <div class="book-info" style="padding: 1rem; display: flex; flex-direction: column; gap: 0.25rem;">
                                <h3 class="book-title" style="font-size: 1.05rem; font-weight: 700; color: #1e293b; line-height: 1.3; overflow: hidden; text-overflow: ellipsis; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; margin: 0;">{{ $book->title }}</h3>
                                <p class="book-author" style="font-size: 0.85rem; color: #64748b; font-weight: 500; margin: 0;">{{ $book->author->name ?? '' }}</p>
                                
                                <div style="display: flex; align-items: center; justify-content: space-between; margin-top: 0.25rem;">
                                    @if($book->average_rating > 0)
                                        <div class="book-rating" style="display: flex; align-items: center; gap: 0.1rem;">
                                            @for($i = 1; $i <= 5; $i++)
                                                <i class="fas fa-star" style="color: {{ $i <= $book->average_rating ? '#fbbf24' : '#e2e8f0' }}; font-size: 0.75rem;"></i>
                                            @endfor
                                            <span style="font-size: 0.75rem; color: #94a3b8; margin-left: 0.2rem;">({{ $book->reviews_count }})</span>
                                        </div>
                                    @else
                                        <span style="font-size: 0.75rem; color: #94a3b8;">Nouveau</span>
                                    @endif
                                </div>
                            </div>
                        </a>
                        
                        <div style="padding: 0 1rem 1rem 1rem; display: flex; flex-direction: column; gap: 0.4rem;">
                            <!-- Stylish Read Button -->
                            <a href="{{ route('books.read', $book) }}" class="btn" style="background: rgba(59, 130, 246, 0.1); color: #3b82f6; width: 100%; border-radius: 8px; font-size: 0.85rem; padding: 0.6rem; font-weight: 600; text-align: center; display: flex; justify-content: center; align-items: center; gap: 0.4rem; transition: background 0.2s; border: none;" onmouseover="this.style.background='rgba(59, 130, 246, 0.2)'" onmouseout="this.style.background='rgba(59, 130, 246, 0.1)'">
                                <i class="fas fa-book-open"></i> {{ __('messages.read_more') }}
                            </a>
                            
                            <!-- Stylish Subscribe Button -->
                            <button type="button" class="btn open-subscribe-modal" data-slug="reader-single" data-price="600" data-name="Achat d'un livre ({{ addslashes($book->title) }})" style="background: linear-gradient(135deg, #f59e0b, #d97706); color: white; width: 100%; border-radius: 8px; font-size: 0.85rem; padding: 0.6rem; font-weight: 600; border: none; cursor: pointer; display: flex; justify-content: center; align-items: center; gap: 0.4rem; transition: transform 0.2s, box-shadow 0.2s; box-shadow: 0 4px 6px -1px rgba(245, 158, 11, 0.2);" onmouseover="this.style.transform='translateY(-1px)'; this.style.boxShadow='0 6px 8px -1px rgba(245, 158, 11, 0.3)'" onmouseout="this.style.transform='none'; this.style.boxShadow='0 4px 6px -1px rgba(245, 158, 11, 0.2)'">
                                <i class="fas fa-unlock-alt"></i> S'abonner
                            </button>
                        </div>
                    </div>
                @endforeach
            </div>

            <div style="margin-top: 2rem; display: flex; justify-content: center;">
                {{ $books->links() }}
            </div>
            
            <x-subscription-modals />
        @else
            <div style="text-align: center; padding: 4rem 0;">
                <i class="fas fa-search" style="font-size: 4rem; color: #d1d5db; margin-bottom: 1rem;"></i>
                <h3 style="color: #64748b;">{{ __('messages.no_books_found') }}</h3>
                <p style="color: #94a3b8;">{{ __('messages.try_different_search') }}</p>
            </div>
        @endif
    </div>
</div>
@endsection
