@extends('layouts.app')
@section('title', $book->title)

@section('content')
<div class="section">
    <div class="container">
        <!-- Book Detail -->
        <div style="display: grid; grid-template-columns: 300px 1fr; gap: 3rem; margin-bottom: 3rem;">
            <div>
                <div class="card card-elevated" style="overflow: hidden; background-color: #f8fafc;">
                    <img src="{{ $book->cover_url }}" alt="{{ $book->title }}" style="width: 100%; aspect-ratio: 2/3; object-fit: contain;">
                </div>

                <div style="margin-top: 1.5rem; display: flex; flex-direction: column; gap: 0.75rem;">
                    @auth
                        @if($userBook)
                            <a href="{{ route('books.read', $book) }}" class="btn btn-primary" style="width: 100%;">
                                <i class="fas fa-book-reader"></i> {{ __('messages.continue_reading') }}
                            </a>
                            <div style="background: rgba(65, 105, 225, 0.1); border-radius: 12px; padding: 0.75rem; text-align: center;">
                                <small style="color: var(--blue-roi);">{{ __('messages.progress') }}: {{ number_format($userBook->progress_percent, 0) }}%</small>
                                <div style="background: #e2e8f0; border-radius: 10px; height: 6px; margin-top: 0.5rem;">
                                    <div style="background: var(--gradient-primary); height: 100%; border-radius: 10px; width: {{ $userBook->progress_percent }}%;"></div>
                                </div>
                            </div>
                        @else
                            <a href="{{ route('books.read', $book) }}" class="btn btn-primary" style="width: 100%;">
                                <i class="fas fa-book-open"></i> {{ __('messages.start_reading') }}
                            </a>
                        @endif
                    @else
                        <a href="{{ route('login') }}" class="btn btn-primary" style="width: 100%;">
                            <i class="fas fa-sign-in-alt"></i> {{ __('messages.login_to_read') }}
                        </a>
                    @endauth
                </div>
            </div>

            <div>
                <div style="margin-bottom: 0.75rem;">
                    <span style="background: {{ $book->type === 'scientific_review' ? 'var(--gradient-secondary)' : 'var(--gradient-primary)' }}; color: white; padding: 0.25rem 0.75rem; border-radius: 20px; font-size: 0.8rem; font-weight: 600;">{{ $book->type_label }}</span>
                    @if($book->is_premium)
                        <span style="background: linear-gradient(135deg, #f59e0b, #d97706); color: white; padding: 0.25rem 0.75rem; border-radius: 20px; font-size: 0.8rem; font-weight: 600;">Premium</span>
                    @endif
                </div>

                <h1 style="font-size: 2rem; margin-bottom: 0.5rem;">{{ $book->title }}</h1>

                <div style="display: flex; align-items: center; gap: 1rem; margin-bottom: 1rem; color: #64748b;">
                    <span><i class="fas fa-user"></i> {{ $book->author->name ?? __('messages.unknown_author') }}</span>
                    <span><i class="fas fa-folder"></i> {{ $book->category->localized_name ?? '' }}</span>
                    <span><i class="fas fa-globe"></i> {{ strtoupper($book->language) }}</span>
                </div>

                @if($book->average_rating > 0)
                    <div style="display: flex; align-items: center; gap: 0.5rem; margin-bottom: 1rem;">
                        @for($i = 1; $i <= 5; $i++)
                            <i class="fas fa-star" style="color: {{ $i <= $book->average_rating ? '#FFA500' : '#d1d5db' }}; font-size: 1.2rem;"></i>
                        @endfor
                        <span style="color: #64748b;">{{ number_format($book->average_rating, 1) }} ({{ $book->reviews_count }} {{ __('messages.reviews') }})</span>
                    </div>
                @endif

                <div style="display: flex; gap: 2rem; margin-bottom: 1.5rem; color: #64748b; font-size: 0.9rem;">
                    <span><i class="fas fa-eye"></i> {{ number_format($book->views_count) }} {{ __('messages.views') }}</span>
                    <span><i class="fas fa-book-reader"></i> {{ number_format($book->reads_count) }} {{ __('messages.reads') }}</span>
                    @if($book->pages_count)
                        <span><i class="fas fa-file-alt"></i> {{ $book->pages_count }} {{ __('messages.pages') }}</span>
                    @endif
                </div>

                <div class="card" style="padding: 1.5rem; margin-bottom: 1.5rem;">
                    <h3 style="margin-bottom: 0.75rem;">{{ __('messages.description') }}</h3>
                    <p style="line-height: 1.8; color: #475569;">{!! nl2br(e($book->description)) !!}</p>
                </div>

                @if($book->keywords && count($book->keywords) > 0)
                    <div style="display: flex; flex-wrap: wrap; gap: 0.5rem; margin-bottom: 1.5rem;">
                        @foreach($book->keywords as $keyword)
                            <span style="background: rgba(65, 105, 225, 0.1); color: var(--blue-roi); padding: 0.25rem 0.75rem; border-radius: 20px; font-size: 0.8rem;">{{ $keyword }}</span>
                        @endforeach
                    </div>
                @endif

                @if($book->publisher)
                    <p style="color: #64748b; font-size: 0.9rem;"><strong>{{ __('messages.publisher') ?? 'Maison d\'édition' }}:</strong> {{ $book->publisher }}</p>
                @endif
                @if($book->isbn)
                    <p style="color: #64748b; font-size: 0.9rem;"><strong>ISBN:</strong> {{ $book->isbn }}</p>
                @endif
                @if($book->doi)
                    <p style="color: #64748b; font-size: 0.9rem;"><strong>DOI:</strong> {{ $book->doi }}</p>
                @endif

                @auth
                    <!-- Report Button -->
                    <div style="margin-top: 1.5rem; display: flex; gap: 0.75rem;">
                        <button onclick="document.getElementById('reportModal').style.display='flex'" class="btn btn-ghost" style="font-size: 0.85rem;">
                            <i class="fas fa-flag"></i> {{ __('messages.report_book') }}
                        </button>
                    </div>
                @endauth
            </div>
        </div>

        <!-- Reviews -->
        <div class="card" style="padding: 2rem; margin-bottom: 2rem;">
            <h2 style="margin-bottom: 1.5rem;">{{ __('messages.reviews') }} ({{ $book->reviews_count }})</h2>

            @auth
                @if(!$userReview)
                    <form action="{{ route('books.review', $book) }}" method="POST" style="margin-bottom: 2rem; padding-bottom: 2rem; border-bottom: 1px solid #e2e8f0;">
                        @csrf
                        <div style="margin-bottom: 1rem;">
                            <label style="font-weight: 600; margin-bottom: 0.5rem; display: block;">{{ __('messages.your_rating') }}</label>
                            <div style="display: flex; gap: 0.25rem;">
                                @for($i = 1; $i <= 5; $i++)
                                    <label style="cursor: pointer; font-size: 1.5rem;">
                                        <input type="radio" name="rating" value="{{ $i }}" style="display: none;" required>
                                        <i class="fas fa-star rating-star" data-rating="{{ $i }}" style="color: #d1d5db; transition: color 0.2s;"></i>
                                    </label>
                                @endfor
                            </div>
                        </div>
                        <div style="margin-bottom: 1rem;">
                            <textarea name="content" rows="3" placeholder="{{ __('messages.write_review') }}" required
                                style="width: 100%; padding: 0.75rem; border: 2px solid #e2e8f0; border-radius: 12px; font-family: 'Roboto', sans-serif; resize: vertical;"></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary">{{ __('messages.submit_review') }}</button>
                    </form>
                @endif
            @endauth

            @forelse($book->approvedReviews as $review)
                <div style="padding: 1rem 0; border-bottom: 1px solid #f1f5f9;">
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 0.5rem;">
                        <div style="display: flex; align-items: center; gap: 0.75rem;">
                            <img src="{{ $review->user->avatar_url }}" alt="" style="width: 36px; height: 36px; border-radius: 50%; object-fit: cover;">
                            <strong>{{ $review->user->name }}</strong>
                            <div>
                                @for($i = 1; $i <= 5; $i++)
                                    <i class="fas fa-star" style="color: {{ $i <= $review->rating ? '#FFA500' : '#d1d5db' }}; font-size: 0.8rem;"></i>
                                @endfor
                            </div>
                        </div>
                        <small style="color: #94a3b8;">{{ $review->created_at->diffForHumans() }}</small>
                    </div>
                    <p style="color: #475569;">{{ $review->content }}</p>
                </div>
            @empty
                <p style="color: #94a3b8; text-align: center; padding: 2rem;">{{ __('messages.no_reviews') }}</p>
            @endforelse
        </div>

        <!-- Related Books -->
        @if($relatedBooks->count() > 0)
            <div>
                <h2 class="section-title" style="margin-bottom: 1.5rem;">{{ __('messages.related_books') }}</h2>
                <div class="grid-books">
                    @foreach($relatedBooks as $related)
                        <a href="{{ route('books.show', $related) }}" class="card card-elevated book-card" style="text-decoration: none;">
                            <div class="book-cover">
                                <img src="{{ $related->cover_url }}" alt="{{ $related->title }}">
                            </div>
                            <div class="book-info">
                                <h3 class="book-title">{{ $related->title }}</h3>
                                <p class="book-author">{{ $related->author->name ?? '' }}</p>
                            </div>
                        </a>
                    @endforeach
                </div>
            </div>
        @endif
    </div>
</div>

<!-- Report Modal -->
@auth
<div id="reportModal" style="display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.5); z-index: 9999; align-items: center; justify-content: center;">
    <div class="card" style="max-width: 500px; width: 90%; padding: 2rem;">
        <h3 style="margin-bottom: 1rem;">{{ __('messages.report_book') }}</h3>
        <form action="{{ route('books.report', $book) }}" method="POST">
            @csrf
            <div style="margin-bottom: 1rem;">
                <select name="reason" required style="width: 100%; padding: 0.75rem; border: 2px solid #e2e8f0; border-radius: 12px;">
                    <option value="">{{ __('messages.select_reason') }}</option>
                    <option value="copyright">{{ __('messages.reason_copyright') }}</option>
                    <option value="inappropriate">{{ __('messages.reason_inappropriate') }}</option>
                    <option value="spam">{{ __('messages.reason_spam') }}</option>
                    <option value="plagiarism">{{ __('messages.reason_plagiarism') }}</option>
                    <option value="other">{{ __('messages.reason_other') }}</option>
                </select>
            </div>
            <div style="margin-bottom: 1rem;">
                <textarea name="description" rows="3" placeholder="{{ __('messages.describe_issue') }}" required
                    style="width: 100%; padding: 0.75rem; border: 2px solid #e2e8f0; border-radius: 12px; font-family: 'Roboto', sans-serif;"></textarea>
            </div>
            <div style="display: flex; gap: 0.75rem; justify-content: flex-end;">
                <button type="button" onclick="document.getElementById('reportModal').style.display='none'" class="btn btn-ghost">{{ __('messages.cancel') }}</button>
                <button type="submit" class="btn btn-primary">{{ __('messages.submit_report') }}</button>
            </div>
        </form>
    </div>
</div>
@endauth

@push('scripts')
<script>
document.querySelectorAll('.rating-star').forEach(star => {
    star.addEventListener('click', function() {
        const rating = parseInt(this.dataset.rating);
        document.querySelectorAll('.rating-star').forEach((s, i) => {
            s.style.color = i < rating ? '#FFA500' : '#d1d5db';
        });
    });
    star.addEventListener('mouseenter', function() {
        const rating = parseInt(this.dataset.rating);
        document.querySelectorAll('.rating-star').forEach((s, i) => {
            s.style.color = i < rating ? '#FFA500' : '#d1d5db';
        });
    });
});
</script>
@endpush
@endsection
