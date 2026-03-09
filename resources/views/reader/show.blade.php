@extends('layouts.app')
@section('title', $book->title . ' - ' . __('messages.reader'))

@push('styles')
<style>
    .reader-container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 1rem;
    }
    .reader-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 0;
        padding: 1.5rem 1.5rem 1rem 1.5rem;
        border-bottom: 1px solid #e2e8f0;
        background: var(--bg-secondary, #f8fafc);
    }
    .reader-content {
        background: white;
        border-radius: 16px;
        padding: 0;
        box-shadow: 0 10px 40px rgba(0,0,0,0.1);
        position: relative;
        overflow: hidden;
    }
    .dark .reader-content {
        background: #1e293b;
    }
    .reader-footer {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding-top: 1.5rem;
        margin-top: 1.5rem;
        border-top: 1px solid #e2e8f0;
    }
    .pdf-viewer {
        width: 100%;
        height: calc(100vh - 280px);
        min-height: 500px;
        border: none;
        display: block;
    }
    .progress-bar-reader {
        position: fixed;
        top: 70px;
        left: 0;
        right: 0;
        height: 4px;
        background: #e2e8f0;
        z-index: 100;
    }
    .progress-bar-fill {
        height: 100%;
        background: var(--gradient-primary);
        transition: width 0.3s ease;
    }
    
    /* Watermark styling */
    #pdf-container {
        position: relative;
    }
    .reader-watermark {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        pointer-events: none;
        z-index: 10;
        opacity: 0;
        overflow: hidden;
        user-select: none;
        display: flex;
        flex-wrap: wrap;
        justify-content: space-around;
        align-content: space-around;
        transition: opacity 0.3s ease;
    }
    .watermark-item {
        transform: rotate(-45deg);
        font-size: 1.2rem;
        font-weight: bold;
        color: #000;
        white-space: nowrap;
        padding: 50px;
    }
</style>
@endpush

@section('content')
<!-- Progress Bar -->
<div class="progress-bar-reader">
    <div class="progress-bar-fill" id="readingProgress" style="width: {{ $userBook->progress_percent }}%"></div>
</div>

<div style="padding-top: 80px; padding-bottom: 20px;">
    <div class="reader-container">
        <div class="reader-content" style="-webkit-user-select: none; -moz-user-select: none; -ms-user-select: none; user-select: none;">
            <div class="reader-header">
                <div>
                    <h2 style="font-size: 1.3rem;">{{ $book->title }}</h2>
                    <p style="color: #64748b; font-size: 0.9rem;">{{ $book->author->name ?? '' }}</p>
                </div>
                <div style="display: flex; gap: 0.75rem; align-items: center;">
                    <span style="color: #64748b; font-size: 0.85rem;">
                        {{ __('messages.page') }} <span id="currentPage">{{ $userBook->current_page ?? 1 }}</span> / {{ $book->pages_count ?? '?' }}
                    </span>

                    <button type="button" class="btn btn-ghost btn-icon" title="{{ __('messages.info') ?? 'Infos' }}" onclick="document.getElementById('bookInfoModal').style.display='flex'">
                        <i class="fas fa-info-circle"></i>
                    </button>
                    <a href="{{ url()->previous() !== url()->current() ? url()->previous() : route('explore') }}" class="btn btn-ghost btn-icon" title="{{ __('messages.close') ?? 'Fermer' }}">
                        <i class="fas fa-times"></i>
                    </a>
                </div>
            </div>

            @if($book->file_type === 'pdf')
                <!-- Custom PDF Viewer with PDF.js -->
                <div class="pdf-controls" style="display: flex; justify-content: center; align-items: center; gap: 1rem; padding: 0.75rem; background: var(--bg-secondary, #f8fafc); border-bottom: 1px solid #e2e8f0;">
                    <button id="prev-page" class="btn btn-ghost btn-sm" title="Précédent"><i class="fas fa-chevron-left"></i></button>
                    <span style="font-weight: 600; font-size: 0.9rem;">Page <span id="page-num">{{ $userBook->current_page ?? 1 }}</span> / <span id="page-count">...</span></span>
                    <button id="next-page" class="btn btn-ghost btn-sm" title="Suivant"><i class="fas fa-chevron-right"></i></button>
                    
                    <div style="width: 1px; height: 20px; background: #cbd5e1; margin: 0 0.5rem;"></div>
                    
                    <button id="zoom-out" class="btn btn-ghost btn-sm" title="Dézoomer"><i class="fas fa-search-minus"></i></button>
                    <button id="zoom-in" class="btn btn-ghost btn-sm" title="Zoomer"><i class="fas fa-search-plus"></i></button>
                </div>
                <div id="pdf-container" style="width: 100%; min-height: 75vh; height: calc(100vh - 150px); overflow: auto; background: #cbd5e1; display: flex; justify-content: center; padding: 2rem 0;">
                    <div class="reader-watermark" id="watermark-overlay"></div>
                    <div>
                        <canvas id="pdf-render" style="box-shadow: 0 4px 15px rgba(0,0,0,0.15); max-width: 100%;"></canvas>
                    </div>
                </div>
            @else
                <div style="text-align: center; padding: 3rem;">
                    <i class="fas fa-book-open" style="font-size: 4rem; color: var(--orange-fluo); margin-bottom: 1rem;"></i>
                    <h3>{{ __('messages.epub_reader') }}</h3>
                    <p style="color: #64748b;">{{ __('messages.epub_coming_soon') }}</p>
                </div>
            @endif

            <div class="reader-footer">
                <div style="display: flex; gap: 0.75rem;">
                    <!-- Bookmark -->
                    <form action="{{ route('books.bookmark', $book) }}" method="POST" style="display: inline;">
                        @csrf
                        <input type="hidden" name="page" id="bookmarkPage" value="{{ $userBook->current_page ?? 1 }}">
                        <button type="submit" class="btn btn-ghost" title="{{ __('messages.add_bookmark') }}">
                            <i class="fas fa-bookmark"></i>
                        </button>
                    </form>
                </div>

                <div style="display: flex; align-items: center; gap: 1rem;">
                    <span style="font-size: 0.85rem; color: #64748b;">{{ number_format($userBook->progress_percent, 0) }}%</span>
                    <div style="width: 200px; background: #e2e8f0; border-radius: 10px; height: 8px;">
                        <div style="background: var(--gradient-primary); height: 100%; border-radius: 10px; width: {{ $userBook->progress_percent }}%;"></div>
                    </div>
                </div>
            </div>
        </div>

        @if($userBook->bookmarks && count($userBook->bookmarks) > 0)
            <div style="margin-top: 1.5rem;">
                <h4 style="margin-bottom: 0.75rem;">{{ __('messages.bookmarks') }}</h4>
                <div style="display: flex; flex-wrap: wrap; gap: 0.5rem;">
                    @foreach($userBook->bookmarks as $bm)
                        <span
                            data-page="{{ $bm['page'] }}"
                            onclick="jumpToBookmark({{ $bm['page'] }})"
                            title="Aller à la page {{ $bm['page'] }}"
                            style="background: rgba(255,165,0,0.1); color: var(--orange-fluo); padding: 0.25rem 0.75rem; border-radius: 20px; font-size: 0.85rem; cursor: pointer; transition: background 0.2s;"
                            onmouseover="this.style.background='rgba(255,165,0,0.25)'"
                            onmouseout="this.style.background='rgba(255,165,0,0.1)'">
                            <i class="fas fa-bookmark"></i> {{ __('messages.page') }} {{ $bm['page'] }}
                            @if(isset($bm['note'])) &mdash; {{ $bm['note'] }} @endif
                        </span>
                    @endforeach
                </div>
            </div>
        @endif
    </div>

    <!-- Book Details & Reviews Section -->
    <div class="reader-container" style="margin-top: 2rem;">
        <div class="grid" style="display: grid; grid-template-columns: 1fr; gap: 2rem;">
            <!-- Description & Info -->
            <div class="card" style="padding: 2rem; background: white; border-radius: 16px; box-shadow: 0 4px 20px rgba(0,0,0,0.05); border: 1px solid #e2e8f0;">
                <h3 style="margin-bottom: 1.5rem; border-bottom: 2px solid var(--blue-roi); display: inline-block; padding-bottom: 0.5rem;">
                    {{ __('messages.description') }}
                </h3>
                <p style="line-height: 1.8; color: #475569; font-size: 1.05rem;">{!! nl2br(e($book->description)) !!}</p>
                
                <div style="margin-top: 2rem; display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1.5rem; padding-top: 1.5rem; border-top: 1px solid #f1f5f9;">
                    @if($book->publisher)
                        <div>
                            <span style="display: block; color: #94a3b8; font-size: 0.8rem; text-transform: uppercase; letter-spacing: 0.05em;">{{ __('messages.publisher') ?? 'Éditeur' }}</span>
                            <span style="font-weight: 600; color: #1e293b;">{{ $book->publisher }}</span>
                        </div>
                    @endif
                    @if($book->isbn)
                        <div>
                            <span style="display: block; color: #94a3b8; font-size: 0.8rem; text-transform: uppercase; letter-spacing: 0.05em;">ISBN</span>
                            <span style="font-weight: 600; color: #1e293b;">{{ $book->isbn }}</span>
                        </div>
                    @endif
                    @if($book->doi)
                        <div>
                            <span style="display: block; color: #94a3b8; font-size: 0.8rem; text-transform: uppercase; letter-spacing: 0.05em;">DOI</span>
                            <span style="font-weight: 600; color: #1e293b;">{{ $book->doi }}</span>
                        </div>
                    @endif
                    <div>
                        <span style="display: block; color: #94a3b8; font-size: 0.8rem; text-transform: uppercase; letter-spacing: 0.05em;">{{ __('messages.category') ?? 'Catégorie' }}</span>
                        <span style="font-weight: 600; color: #1e293b;">{{ $book->category->localized_name ?? '' }}</span>
                    </div>
                </div>
            </div>

            <!-- Reviews Section -->
            <div class="card" style="padding: 2rem; background: white; border-radius: 16px; box-shadow: 0 4px 20px rgba(0,0,0,0.05); border: 1px solid #e2e8f0;">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
                    <h3 style="margin: 0; border-bottom: 2px solid var(--orange-fluo); display: inline-block; padding-bottom: 0.5rem;">
                        {{ __('messages.reviews') }} ({{ $book->reviews_count }})
                    </h3>
                    @if($book->average_rating > 0)
                        <div style="display: flex; align-items: center; gap: 0.5rem;">
                            <span style="font-weight: 700; color: #1e293b; font-size: 1.2rem;">{{ number_format($book->average_rating, 1) }}</span>
                            <div style="display: flex; gap: 2px;">
                                @for($i = 1; $i <= 5; $i++)
                                    <i class="fas fa-star" style="color: {{ $i <= $book->average_rating ? '#FFA500' : '#d1d5db' }}; font-size: 0.9rem;"></i>
                                @endfor
                            </div>
                        </div>
                    @endif
                </div>

                @auth
                    @php
                        $alreadyReviewed = $book->reviews()->where('user_id', auth()->id())->exists();
                    @endphp
                    @if(!$alreadyReviewed)
                        <div style="background: #f8fafc; border-radius: 12px; padding: 1.5rem; margin-bottom: 2rem; border: 1px solid #e2e8f0;">
                            <h4 style="margin-top: 0; margin-bottom: 1rem; color: #1e293b;">{{ __('messages.write_review') ?? 'Laissez votre avis' }}</h4>
                            <form action="{{ route('books.review', $book) }}" method="POST">
                                @csrf
                                <div style="margin-bottom: 1rem;">
                                    <div style="display: flex; gap: 0.5rem;">
                                        @for($i = 1; $i <= 5; $i++)
                                            <label style="cursor: pointer; font-size: 1.5rem;">
                                                <input type="radio" name="rating" value="{{ $i }}" style="display: none;" required>
                                                <i class="fas fa-star rating-star-page" data-rating="{{ $i }}" style="color: #d1d5db; transition: color 0.2s;"></i>
                                            </label>
                                        @endfor
                                    </div>
                                </div>
                                <div style="margin-bottom: 1rem;">
                                    <textarea name="content" rows="3" placeholder="{{ __('messages.write_review') ?? 'Votre avis...' }}" required
                                        style="width: 100%; padding: 1rem; border: 2px solid #e2e8f0; border-radius: 12px; resize: vertical;"></textarea>
                                </div>
                                <button type="submit" class="btn btn-primary">{{ __('messages.submit_review') ?? 'Publier l\'avis' }}</button>
                            </form>
                        </div>
                    @endif
                @endauth

                <div style="display: flex; flex-direction: column; gap: 1.5rem;">
                    @forelse($book->approvedReviews()->with('user')->latest()->get() as $review)
                        <div style="padding-bottom: 1.5rem; border-bottom: 1px solid #f1f5f9;">
                            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 0.75rem;">
                                <div style="display: flex; align-items: center; gap: 1rem;">
                                    <img src="{{ $review->user->avatar_url }}" alt="" style="width: 42px; height: 42px; border-radius: 50%; object-fit: cover; border: 2px solid #fff; box-shadow: 0 2px 8px rgba(0,0,0,0.1);">
                                    <div>
                                        <div style="font-weight: 700; color: #1e293b; font-size: 0.95rem;">{{ $review->user->name }}</div>
                                        <div style="display: flex; gap: 1px;">
                                            @for($i = 1; $i <= 5; $i++)
                                                <i class="fas fa-star" style="color: {{ $i <= $review->rating ? '#FFA500' : '#d1d5db' }}; font-size: 0.75rem;"></i>
                                            @endfor
                                        </div>
                                    </div>
                                </div>
                                <small style="color: #94a3b8; font-size: 0.8rem;">{{ $review->created_at->diffForHumans() }}</small>
                            </div>
                            <p style="color: #475569; margin: 0; line-height: 1.6;">{{ $review->content }}</p>
                        </div>
                    @empty
                        <div style="text-align: center; padding: 2rem; color: #94a3b8;">
                            <i class="fas fa-comment-slash" style="font-size: 2rem; margin-bottom: 1rem; display: block; opacity: 0.5;"></i>
                            {{ __('messages.no_reviews') ?? 'Aucun avis pour le moment.' }}
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Book Info Modal -->
<div id="bookInfoModal" class="modal" tabindex="-1" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; justify-content: center; align-items: center; z-index: 1050; background: rgba(0,0,0,0.5);">
    <div class="modal-dialog" style="background: white; border-radius: 12px; width: 90%; max-width: 600px; padding: 2rem; box-shadow: 0 10px 25px rgba(0,0,0,0.1); position: relative; max-height: 80vh; overflow-y: auto;">
        <button type="button" class="close-modal" style="position: absolute; top: 1rem; right: 1.5rem; background: none; border: none; font-size: 1.5rem; cursor: pointer; color: #64748b;" onclick="document.getElementById('bookInfoModal').style.display='none'">&times;</button>
        
        <div style="display: flex; gap: 1.5rem; flex-wrap: wrap; margin-top: 1rem;">
            <div style="flex: 1; min-width: 150px; max-width: 200px;">
                <img src="{{ $book->cover_url }}" alt="{{ $book->title }}" style="width: 100%; border-radius: 8px; box-shadow: 0 4px 10px rgba(0,0,0,0.1);">
            </div>
            <div style="flex: 2; min-width: 250px;">
                <h2 style="margin-bottom: 0.5rem; color: #1e293b;">{{ $book->title }}</h2>
                <p style="color: var(--blue-roi); font-weight: 600; margin-bottom: 1rem;">{{ $book->author->name ?? '' }}</p>
                
                <div style="display: flex; gap: 1rem; margin-bottom: 1rem; font-size: 0.9rem; color: #64748b;">
                    <span><i class="fas fa-layer-group"></i> {{ $book->category->localized_name ?? '' }}</span>
                    <span><i class="fas fa-file-alt"></i> {{ $book->pages_count }} pages</span>
                    <span><i class="fas fa-language"></i> {{ strtoupper($book->language) }}</span>
                </div>

                <div style="margin-bottom: 1rem;">
                    <h4 style="margin-bottom: 0.5rem;">{{ __('messages.description') }}</h4>
                    <p style="color: #475569; font-size: 0.95rem; line-height: 1.6;">
                        {{ $book->description }}
                    </p>
                </div>
                
                @auth
                    @php
                        $userReview = $book->reviews()->where('user_id', auth()->id())->first();
                    @endphp
                    @if(!$userReview)
                        <div style="margin-top: 2rem; border-top: 1px solid #e2e8f0; padding-top: 1.5rem;">
                            <button type="button" class="btn btn-outline" style="width: 100%; font-size: 0.9rem;" onclick="document.getElementById('reviewFormContainer').style.display='block'; this.style.display='none';">
                                <i class="fas fa-star"></i> {{ __('messages.write_review') ?? 'Laisser un avis' }}
                            </button>
                            
                            <div id="reviewFormContainer" style="display: none; margin-top: 1rem;">
                                <h4 style="margin-bottom: 1rem;">{{ __('messages.your_rating') ?? 'Votre note' }}</h4>
                                <form action="{{ route('books.review', $book) }}" method="POST">
                                    @csrf
                                    <div style="margin-bottom: 1rem;">
                                        <div style="display: flex; gap: 0.25rem;">
                                            @for($i = 1; $i <= 5; $i++)
                                                <label style="cursor: pointer; font-size: 1.5rem;">
                                                    <input type="radio" name="rating" value="{{ $i }}" style="display: none;" required>
                                                    <i class="fas fa-star rating-star-modal" data-rating="{{ $i }}" style="color: #d1d5db; transition: color 0.2s;"></i>
                                                </label>
                                            @endfor
                                        </div>
                                    </div>
                                    <div style="margin-bottom: 1rem;">
                                        <textarea name="content" rows="3" placeholder="{{ __('messages.write_review') ?? 'Écrivez votre avis ici...' }}" required
                                            style="width: 100%; padding: 0.75rem; border: 2px solid #e2e8f0; border-radius: 12px; font-family: 'Roboto', sans-serif; resize: vertical;"></textarea>
                                    </div>
                                    <div style="display: flex; justify-content: flex-end; gap: 0.5rem;">
                                        <button type="button" class="btn btn-ghost" onclick="document.getElementById('reviewFormContainer').style.display='none'; this.closest('.modal-dialog').querySelector('.btn-outline').style.display='block';">Annuler</button>
                                        <button type="submit" class="btn btn-primary">{{ __('messages.submit_review') ?? 'Envoyer' }}</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    @endif
                @endauth
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.min.js"></script>
<script>
@if($book->file_type === 'pdf')
// Initialize PDF.js
pdfjsLib.GlobalWorkerOptions.workerSrc = 'https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.worker.min.js';

const url = '{!! $book->file_url !!}';
let pdfDoc = null,
    pageNum = {{ $userBook->current_page ?? 1 }},
    pageIsRendering = false,
    pageNumIsPending = null,
    scale = 1.0,
    canvas = document.querySelector('#pdf-render'),
    ctx = canvas ? canvas.getContext('2d') : null;

let readingStartTime = Date.now();
let isInitialLoad = true;

// Render the page
const renderPage = num => {
    pageIsRendering = true;

    pdfDoc.getPage(num).then(page => {
        const viewport = page.getViewport({ scale });
        canvas.height = viewport.height;
        canvas.width = viewport.width;

        const renderCtx = {
            canvasContext: ctx,
            viewport: viewport
        };

        page.render(renderCtx).promise.then(() => {
            pageIsRendering = false;
            if(pageNumIsPending !== null) {
                renderPage(pageNumIsPending);
                pageNumIsPending = null;
            }
        });

        // Update page counters
        const pageNumEl = document.querySelector('#page-num');
        const currentPgEl = document.querySelector('#currentPage');
        const bookmarkInput = document.querySelector('#bookmarkPage');
        if(pageNumEl) pageNumEl.textContent = num;
        if(currentPgEl) currentPgEl.textContent = num;
        if(bookmarkInput) bookmarkInput.value = num;
        
        // Sync page to server instantly (skip if initial load just setting the saved page)
        if (!isInitialLoad) {
            updateProgressOnBackend(num);
        } else {
            isInitialLoad = false; // Next change will be user-triggered
        }
    });
};

// Check for pending renders
const queueRenderPage = num => {
    if(pageIsRendering) {
        pageNumIsPending = num;
    } else {
        renderPage(num);
    }
};

const showPrevPage = () => {
    if(pageNum <= 1) return;
    pageNum--;
    queueRenderPage(pageNum);
};

const showNextPage = () => {
    if(pageNum >= pdfDoc.numPages) return;
    pageNum++;
    queueRenderPage(pageNum);
};

// Zoom Functions
document.querySelector('#zoom-in')?.addEventListener('click', () => {
    if (scale >= 3.0) return;
    scale += 0.2;
    queueRenderPage(pageNum);
});

document.querySelector('#zoom-out')?.addEventListener('click', () => {
    if(scale <= 0.6) return;
    scale -= 0.2;
    queueRenderPage(pageNum);
});

// Get the document
if (url && canvas) {
    pdfjsLib.getDocument(url).promise.then(pdfDoc_ => {
        pdfDoc = pdfDoc_;
        const pgCountEl = document.querySelector('#page-count');
        if(pgCountEl) pgCountEl.textContent = pdfDoc.numPages;
        
        // Adjust max page if needed
        if(pageNum > pdfDoc.numPages) pageNum = pdfDoc.numPages;
        
        renderPage(pageNum);
    }).catch(err => {
        console.error(err);
        const container = document.querySelector('#pdf-container');
        if (container) {
            container.innerHTML = '<div style="color: #ef4444; padding: 2rem; text-align: center;">Impossible de charger le document PDF. Le fichier est peut-être corrompu ou inaccessible.</div>';
        }
    });

    // Button Events
    document.querySelector('#prev-page')?.addEventListener('click', showPrevPage);
    document.querySelector('#next-page')?.addEventListener('click', showNextPage);

    // Scroll Events for Automatic Page Turn
    let lastScrollTop = 0;
    document.querySelector('#pdf-container')?.addEventListener('scroll', (e) => {
        if (pageIsRendering || !pdfDoc) return;
        
        const container = e.currentTarget;
        const currentScrollTop = container.scrollTop;
        const scrollingDown = currentScrollTop > lastScrollTop;
        const scrollingUp = currentScrollTop < lastScrollTop;
        
        lastScrollTop = currentScrollTop;
        
        const isAtBottom = currentScrollTop + container.clientHeight >= container.scrollHeight - 2;
        const isAtTop = currentScrollTop === 0;
        
        if (scrollingDown && isAtBottom && pageNum < pdfDoc.numPages) {
            showNextPage();
            // Reset scroll position gracefully
            setTimeout(() => {
                container.scrollTop = 1; 
                lastScrollTop = 1;
            }, 100);
        } else if (scrollingUp && isAtTop && pageNum > 1) {
            showPrevPage();
            // Reset scroll position to bottom 
            setTimeout(() => {
                container.scrollTop = container.scrollHeight - container.clientHeight - 1;
                lastScrollTop = container.scrollTop;
            }, 100);
        }
    });
}

function updateProgressOnBackend(pageNumber) {
    const elapsedMinutes = Math.floor((Date.now() - readingStartTime) / 60000);
    
    fetch('{{ route("books.progress", $book) }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({
            current_page: pageNumber,
            reading_time: elapsedMinutes > 0 ? elapsedMinutes : 0
        })
    }).then(r => r.json()).then(data => {
        if (data.progress) {
            const progBar = document.getElementById('readingProgress');
            if(progBar) progBar.style.width = data.progress + '%';
        }
    });
    
    // Always reset reading start time after a network call to avoid dupes
    readingStartTime = Date.now(); 
}

// Interval to save time periodically if user stays on same page for a while
setInterval(function() {
    const elapsedMinutes = Math.floor((Date.now() - readingStartTime) / 60000);
    if (elapsedMinutes > 0 && typeof pageNum !== 'undefined') {
        updateProgressOnBackend(pageNum);
    }
}, 60000); // Check every minute
@else
// Track reading progress periodically
let readingStartTime = Date.now();
let lastSavedPage = {{ $userBook->current_page ?? 1 }};

setInterval(function() {
    const elapsedMinutes = Math.floor((Date.now() - readingStartTime) / 60000);
    if (elapsedMinutes > 0) {
        fetch('{{ route("books.progress", $book) }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({
                current_page: lastSavedPage,
                reading_time: elapsedMinutes
            })
        }).then(r => r.json()).then(data => {
            if (data.progress) {
                document.getElementById('readingProgress').style.width = data.progress + '%';
            }
        });
        readingStartTime = Date.now();
    }
}, 60000); // Every minute
@endif

// Protection anticopie & capture d'écran basique
document.addEventListener('contextmenu', event => event.preventDefault());
document.addEventListener('selectstart', event => event.preventDefault());
document.addEventListener('dragstart', event => event.preventDefault());

// Generating Watermark
function generateWatermark() {
    const overlay = document.getElementById('watermark-overlay');
    if (!overlay) return;
    
    const text = '{{ auth()->user()->name }} - ID: {{ auth()->id() }} - ' + new Date().toLocaleDateString();
    overlay.innerHTML = '';
    
    // Create enough watermark items to cover the container
    for (let i = 0; i < 50; i++) {
        const item = document.createElement('div');
        item.className = 'watermark-item';
        item.textContent = text;
        overlay.appendChild(item);
    }
}
generateWatermark();

document.addEventListener('keydown', function(e) {
    // Disable F12, Ctrl+Shift+I, Ctrl+Shift+J, Ctrl+U, Ctrl+S, Ctrl+P, PrintScreen
    if(e.keyCode == 123) { e.preventDefault(); return false; } // F12
    if(e.ctrlKey && e.shiftKey && (e.keyCode == 73 || e.keyCode == 74 || e.keyCode == 67)) { e.preventDefault(); return false; } // Ctrl+Shift+I/J/C
    if(e.ctrlKey && (e.keyCode == 85 || e.keyCode == 83 || e.keyCode == 80 || e.keyCode == 67)) { e.preventDefault(); return false; } // Ctrl+U/S/P/C
    
    // More screenshot shortcuts (OS Specific)
    if((e.metaKey || e.ctrlKey) && e.shiftKey && (e.keyCode == 51 || e.keyCode == 52 || e.keyCode == 53 || e.keyCode == 115)) {
        // Mac CMD+Shift+3/4/5 or Win Snipping
        const watermark = document.getElementById('watermark-overlay');
        if (watermark) watermark.style.opacity = '0.3';
        document.body.style.display = 'none';
        setTimeout(() => {
            document.body.style.display = 'block';
            if (watermark) watermark.style.opacity = '0';
        }, 1000);
    }

    if(e.key === 'PrintScreen' || e.keyCode == 44 || e.key === 'Insert' || e.keyCode == 45) {
        const watermark = document.getElementById('watermark-overlay');
        if (watermark) watermark.style.opacity = '0.3';
        navigator.clipboard.writeText(''); // Clear clipboard
        document.body.style.display = 'none'; // Hide body temporarily
        setTimeout(() => {
            document.body.style.display = 'block';
            if (watermark) watermark.style.opacity = '0';
        }, 1000);
    }
});

document.addEventListener('keyup', function(e) {
    if(e.key === 'PrintScreen' || e.keyCode == 44) {
        if (navigator.clipboard && window.isSecureContext) {
            navigator.clipboard.writeText('');
        }
    }
});

// Flouter le lecteur lorsque la fenêtre perd le focus
window.addEventListener('blur', function() {
    const content = document.querySelector('.reader-content');
    const watermark = document.getElementById('watermark-overlay');
    if (content) {
        content.style.filter = 'blur(25px)';
    }
    if (watermark) {
        watermark.style.opacity = '0.3';
    }
});

window.addEventListener('focus', function() {
    const content = document.querySelector('.reader-content');
    const watermark = document.getElementById('watermark-overlay');
    if (content) {
        content.style.filter = 'none';
    }
    if (watermark) {
        watermark.style.opacity = '0';
    }
});

// Close info modal when clicking outside
window.addEventListener('click', (e) => {
    const modal = document.getElementById('bookInfoModal');
    if (e.target === modal) {
        modal.style.display = 'none';
        // Reset review form if open
        const reviewForm = document.getElementById('reviewFormContainer');
        if (reviewForm) {
            reviewForm.style.display = 'none';
            modal.querySelector('.btn-outline').style.display = 'block';
        }
    }
});

// Star rating behavior in modal
document.querySelectorAll('.rating-star-modal').forEach(star => {
    star.addEventListener('click', function() {
        const rating = parseInt(this.dataset.rating);
        document.querySelectorAll('.rating-star-modal').forEach((s, i) => {
            s.style.color = i < rating ? '#FFA500' : '#d1d5db';
        });
        // Check the hidden radio button
        this.previousElementSibling.checked = true;
    });
    
    // Optional hover effect
    star.addEventListener('mouseenter', function() {
        const rating = parseInt(this.dataset.rating);
        document.querySelectorAll('.rating-star-modal').forEach((s, i) => {
            s.style.color = i < rating ? '#FFA500' : '#d1d5db';
        });
    });
    
    star.parentElement.parentElement.addEventListener('mouseleave', function() {
        // Reset to checked radio value
        let checkedValue = 0;
        const checkedRadio = this.parentElement.parentElement.querySelector('input[type="radio"]:checked');
        if (checkedRadio) checkedValue = parseInt(checkedRadio.value);
        
        this.querySelectorAll('.rating-star-modal').forEach((s, i) => {
            s.style.color = i < checkedValue ? '#FFA500' : '#d1d5db';
        });
    });
});

// Star rating behavior on page (below reader)
document.querySelectorAll('.rating-star-page').forEach(star => {
    star.addEventListener('click', function() {
        const rating = parseInt(this.dataset.rating);
        const container = this.closest('div');
        container.querySelectorAll('.rating-star-page').forEach((s, i) => {
            s.style.color = i < rating ? '#FFA500' : '#d1d5db';
        });
        // Check the hidden radio button
        this.previousElementSibling.checked = true;
    });
    
    star.addEventListener('mouseenter', function() {
        const rating = parseInt(this.dataset.rating);
        const container = this.closest('div');
        container.querySelectorAll('.rating-star-page').forEach((s, i) => {
            s.style.color = i < rating ? '#FFA500' : '#d1d5db';
        });
    });
    
    const starsContainer = star.closest('div');
    starsContainer.addEventListener('mouseleave', function() {
        let checkedValue = 0;
        const checkedRadio = this.querySelector('input[type="radio"]:checked');
        if (checkedRadio) checkedValue = parseInt(checkedRadio.value);
        
        this.querySelectorAll('.rating-star-page').forEach((s, i) => {
            s.style.color = i < checkedValue ? '#FFA500' : '#d1d5db';
        });
    });
});
</script>

@push('scripts')
<script>
function jumpToBookmark(targetPage) {
    if (typeof pdfDoc === 'undefined' || !pdfDoc) {
        // Non-PDF fallback: just scroll to top
        window.scrollTo({ top: 0, behavior: 'smooth' });
        return;
    }
    if (targetPage < 1 || targetPage > pdfDoc.numPages) return;
    pageNum = targetPage;
    queueRenderPage(pageNum);
    // Scroll the PDF container to the top so user sees the new page
    const container = document.getElementById('pdf-container');
    if (container) container.scrollTop = 0;
    // Sync with backend immediately
    updateProgressOnBackend(targetPage);
}
</script>
@endpush
