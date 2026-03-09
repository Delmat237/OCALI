@extends('layouts.app')

@section('title', __('messages.home'))

@section('content')
<style>
    /* ===== HERO SECTION ===== */
    .hero {
        min-height: calc(100vh - 70px);
        display: flex;
        align-items: center;
        position: relative;
        overflow: hidden;
        padding: 4rem 0;
    }

    .hero-container {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 4rem;
        align-items: center;
    }

    @media (max-width: 1024px) {
        .hero-container {
            grid-template-columns: 1fr;
            text-align: center;
        }
    }

    .hero-content {
        z-index: 2;
    }

    .hero-badge {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        background: rgba(255, 165, 0, 0.1);
        border: 1px solid rgba(255, 165, 0, 0.3);
        padding: 0.5rem 1rem;
        border-radius: 50px;
        font-size: 0.85rem;
        color: var(--orange-fluo);
        margin-bottom: 1.5rem;
        animation: slideInLeft 0.6s ease-out;
    }

    .hero-title {
        font-size: 3.5rem;
        line-height: 1.1;
        margin-bottom: 1.5rem;
        animation: slideInUp 0.6s ease-out 0.1s both;
    }

    @media (max-width: 768px) {
        .hero-title {
            font-size: 2.5rem;
        }
    }

    .hero-title span {
        display: block;
    }

    .hero-description {
        font-size: 1.2rem;
        color: #64748b;
        line-height: 1.8;
        margin-bottom: 2rem;
        max-width: 500px;
        animation: slideInUp 0.6s ease-out 0.2s both;
    }

    .dark .hero-description {
        color: #94a3b8;
    }

    @media (max-width: 1024px) {
        .hero-description {
            margin: 0 auto 2rem;
        }
    }

    .hero-actions {
        display: flex;
        gap: 1rem;
        flex-wrap: wrap;
        animation: slideInUp 0.6s ease-out 0.3s both;
    }

    @media (max-width: 1024px) {
        .hero-actions {
            justify-content: center;
        }
    }

    .hero-stats {
        display: flex;
        gap: 3rem;
        margin-top: 3rem;
        animation: slideInUp 0.6s ease-out 0.4s both;
    }

    @media (max-width: 1024px) {
        .hero-stats {
            justify-content: center;
        }
    }

    .stat-item {
        text-align: center;
    }

    .stat-value {
        font-family: 'Poppins', sans-serif;
        font-size: 2rem;
        font-weight: 800;
        background: var(--gradient-secondary);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }

    .stat-label {
        font-size: 0.9rem;
        color: #64748b;
    }

    /* ===== HERO VISUAL ===== */
    .hero-visual {
        position: relative;
        display: flex;
        justify-content: center;
        align-items: center;
        animation: fadeIn 1s ease-out 0.3s both;
    }

    .hero-books-showcase {
        position: relative;
        width: 100%;
        max-width: 500px;
        height: 500px;
    }

    .showcase-book {
        position: absolute;
        width: 160px;
        border-radius: 15px;
        overflow: hidden;
        box-shadow: 0 25px 50px rgba(0, 0, 0, 0.3);
        transition: all 0.5s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .showcase-book img {
        width: 100%;
        height: 240px;
        object-fit: contain;
        background-color: #f8fafc;
    }

    .showcase-book:nth-child(1) {
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%) rotate(-5deg);
        z-index: 3;
        animation: floatBook1 6s ease-in-out infinite;
    }

    .showcase-book:nth-child(2) {
        top: 20%;
        left: 10%;
        transform: rotate(10deg);
        z-index: 2;
        animation: floatBook2 7s ease-in-out infinite;
    }

    .showcase-book:nth-child(3) {
        bottom: 15%;
        right: 10%;
        transform: rotate(-10deg);
        z-index: 1;
        animation: floatBook3 8s ease-in-out infinite;
    }

    @keyframes floatBook1 {
        0%, 100% { transform: translate(-50%, -50%) rotate(-5deg); }
        50% { transform: translate(-50%, calc(-50% - 15px)) rotate(-3deg); }
    }

    @keyframes floatBook2 {
        0%, 100% { transform: rotate(10deg) translateY(0); }
        50% { transform: rotate(12deg) translateY(-20px); }
    }

    @keyframes floatBook3 {
        0%, 100% { transform: rotate(-10deg) translateY(0); }
        50% { transform: rotate(-8deg) translateY(-10px); }
    }

    /* Decorative shapes around books */
    .hero-shape {
        position: absolute;
        z-index: 0;
    }

    .hero-circle-1 {
        width: 400px;
        height: 400px;
        border: 3px solid rgba(255, 165, 0, 0.2);
        border-radius: 50%;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        animation: pulse 4s ease-in-out infinite;
    }

    .hero-circle-2 {
        width: 300px;
        height: 300px;
        border: 3px dashed rgba(65, 105, 225, 0.2);
        border-radius: 50%;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        animation: rotate 20s linear infinite;
    }

    /* ===== CATEGORIES CAROUSEL ===== */
    .categories-section {
        padding: 4rem 0;
        background: linear-gradient(180deg, transparent 0%, rgba(65, 105, 225, 0.05) 50%, transparent 100%);
    }

    .categories-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
        gap: 1.5rem;
    }

    .category-card {
        background: white;
        border-radius: 20px;
        padding: 2rem 1.5rem;
        text-align: center;
        text-decoration: none;
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
    }

    .dark .category-card {
        background: #1e293b;
    }

    .category-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: var(--gradient-primary);
        transform: scaleX(0);
        transition: transform 0.3s ease;
    }

    .category-card:hover::before {
        transform: scaleX(1);
    }

    .category-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
    }

    .category-icon {
        width: 60px;
        height: 60px;
        background: var(--gradient-primary);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 1rem;
        font-size: 1.5rem;
        color: white;
        transform: rotate(0deg);
        transition: transform 0.3s ease;
    }

    .category-card:hover .category-icon {
        transform: rotate(10deg) scale(1.1);
    }

    .category-name {
        font-family: 'Poppins', sans-serif;
        font-weight: 600;
        color: #1e293b;
        margin-bottom: 0.25rem;
    }

    .dark .category-name {
        color: #f1f5f9;
    }

    .category-count {
        font-size: 0.85rem;
        color: #64748b;
    }

    /* ===== FEATURED BOOKS SECTION ===== */
    .featured-section {
        padding: 5rem 0;
        position: relative;
    }

    .featured-section::before {
        content: '';
        position: absolute;
        top: 50%;
        right: 0;
        width: 300px;
        height: 300px;
        background: var(--gradient-primary);
        border-radius: 50%;
        opacity: 0.05;
        filter: blur(80px);
        transform: translateY(-50%);
    }

    /* ===== PREMIUM CTA SECTION ===== */
    .premium-cta {
        padding: 6rem 0;
        position: relative;
        overflow: hidden;
    }

    .premium-cta-card {
        background: var(--gradient-dark);
        border-radius: 30px;
        padding: 4rem;
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 3rem;
        align-items: center;
        position: relative;
        overflow: hidden;
    }

    @media (max-width: 1024px) {
        .premium-cta-card {
            grid-template-columns: 1fr;
            text-align: center;
            padding: 3rem 2rem;
        }
    }

    .premium-cta-card::before {
        content: '';
        position: absolute;
        top: -100px;
        right: -100px;
        width: 300px;
        height: 300px;
        background: var(--gradient-primary);
        border-radius: 50%;
        opacity: 0.1;
    }

    .premium-cta-card::after {
        content: '';
        position: absolute;
        bottom: -150px;
        left: -150px;
        width: 400px;
        height: 400px;
        background: var(--gradient-secondary);
        border-radius: 50%;
        opacity: 0.1;
    }

    .premium-content {
        position: relative;
        z-index: 1;
    }

    .premium-badge {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        background: rgba(255, 165, 0, 0.2);
        padding: 0.5rem 1rem;
        border-radius: 50px;
        font-size: 0.85rem;
        color: var(--orange-fluo);
        margin-bottom: 1.5rem;
    }

    .premium-title {
        font-size: 2.5rem;
        color: white;
        margin-bottom: 1rem;
    }

    .premium-description {
        color: #94a3b8;
        font-size: 1.1rem;
        line-height: 1.8;
        margin-bottom: 2rem;
    }

    .premium-features {
        list-style: none;
        margin-bottom: 2rem;
    }

    .premium-features li {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        color: #e2e8f0;
        margin-bottom: 0.75rem;
    }

    .premium-features li i {
        color: var(--orange-fluo);
    }

    .premium-visual {
        position: relative;
        z-index: 1;
        display: flex;
        justify-content: center;
    }

    .pricing-preview {
        background: rgba(255, 255, 255, 0.1);
        backdrop-filter: blur(10px);
        border-radius: 20px;
        padding: 2rem;
        text-align: center;
        border: 1px solid rgba(255, 255, 255, 0.1);
    }

    .pricing-from {
        color: #94a3b8;
        font-size: 0.9rem;
    }

    .pricing-amount {
        font-size: 3rem;
        font-weight: 800;
        color: white;
        line-height: 1;
    }

    .pricing-amount span {
        font-size: 1rem;
        font-weight: 400;
    }

    .pricing-period {
        color: #64748b;
        margin-bottom: 1.5rem;
    }

    /* ===== NEWSLETTER SECTION ===== */
    .newsletter-section {
        padding: 5rem 0;
        background: linear-gradient(180deg, transparent 0%, rgba(65, 105, 225, 0.03) 100%);
    }

    .newsletter-card {
        background: white;
        border-radius: 30px;
        padding: 4rem;
        text-align: center;
        position: relative;
        overflow: hidden;
        box-shadow: 0 20px 60px rgba(0, 0, 0, 0.1);
    }

    .dark .newsletter-card {
        background: #1e293b;
    }

    .newsletter-card::before,
    .newsletter-card::after {
        content: '';
        position: absolute;
        width: 200px;
        height: 200px;
        border-radius: 50%;
        opacity: 0.1;
    }

    .newsletter-card::before {
        background: var(--gradient-primary);
        top: -100px;
        left: -100px;
    }

    .newsletter-card::after {
        background: var(--gradient-secondary);
        bottom: -100px;
        right: -100px;
    }

    .newsletter-icon {
        width: 80px;
        height: 80px;
        background: var(--gradient-primary);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 2rem;
        font-size: 2rem;
        color: white;
    }

    .newsletter-title {
        font-size: 2rem;
        margin-bottom: 1rem;
    }

    .newsletter-description {
        color: #64748b;
        max-width: 500px;
        margin: 0 auto 2rem;
    }

    .newsletter-form {
        display: flex;
        gap: 1rem;
        max-width: 500px;
        margin: 0 auto;
    }

    @media (max-width: 600px) {
        .newsletter-form {
            flex-direction: column;
        }
    }

    .newsletter-input {
        flex: 1;
        padding: 1rem 1.5rem;
        border: 2px solid #e2e8f0;
        border-radius: 50px;
        font-size: 1rem;
        transition: border-color 0.3s ease;
        background: transparent;
    }

    .dark .newsletter-input {
        border-color: #475569;
        color: #f1f5f9;
    }

    .newsletter-input:focus {
        outline: none;
        border-color: var(--orange-fluo);
    }
</style>

<!-- Hero Section -->
<section class="hero">
    <div class="container">
        <div class="hero-container">
            <div class="hero-content">
                <div class="hero-badge">
                    <i class="fas fa-sparkles"></i>
                    {{ __('messages.hero_badge') }}
                </div>

                <h1 class="hero-title">
                    <span>{{ __('messages.hero_title_1') }}</span>
                    <span class="text-gradient">{{ __('messages.hero_title_2') }}</span>
                    <span class="text-gradient-blue">{{ __('messages.hero_title_3') }}</span>
                </h1>

                <p class="hero-description">
                    {{ __('messages.hero_description') }}
                </p>

                <div class="hero-actions">
                    <a href="{{ route('explore') }}" class="btn btn-primary btn-lg">
                        <i class="fas fa-compass"></i>
                        {{ __('messages.start_exploring') }}
                    </a>
                    <a href="{{ route('pricing') }}" class="btn btn-outline btn-lg">
                        {{ __('messages.view_plans') }}
                    </a>
                </div>

                <div class="hero-stats">
                    <div class="stat-item">
                        <div class="stat-value">10K+</div>
                        <div class="stat-label">{{ __('messages.books') }}</div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-value">50K+</div>
                        <div class="stat-label">{{ __('messages.readers') }}</div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-value">1K+</div>
                        <div class="stat-label">{{ __('messages.authors') }}</div>
                    </div>
                </div>
            </div>

            <div class="hero-visual">
                <div class="hero-books-showcase">
                    <div class="hero-shape hero-circle-1"></div>
                    <div class="hero-shape hero-circle-2"></div>

                    @foreach($featuredBooks->take(3) as $book)
                    <div class="showcase-book">
                        <img src="{{ $book->cover_url }}" alt="{{ $book->title }}">
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Mission Section -->
<section class="section" style="padding: 3rem 0; background: #f8fafc;">
    <div class="container">
        <div class="card card-elevated" style="padding: 3rem; text-align: center; border-left: 5px solid var(--orange-fluo);">
            <h2 class="section-title" style="margin-bottom: 1rem; color: #1e293b;">{{ __('messages.mission_title') }}</h2>
            <p style="font-size: 1.2rem; line-height: 1.8; color: #64748b; max-width: 800px; margin: 0 auto;">
                {{ __('messages.mission_description') }}
            </p>
        </div>
    </div>
</section>

<!-- Categories Section -->
<section class="categories-section">
    <div class="container">
        <div class="section-header">
            <h2 class="section-title">{{ __('messages.browse_categories') }}</h2>
            <a href="{{ route('categories') }}" class="btn btn-ghost">
                {{ __('messages.view_all') }}
                <i class="fas fa-arrow-right"></i>
            </a>
        </div>

        <div class="categories-grid">
            @foreach($categories as $category)
            <a href="{{ route('category.show', $category) }}" class="category-card animate-on-scroll">
                <div class="category-icon" style="background: linear-gradient(135deg, {{ $category->color }}, {{ $category->color }}dd);">
                    <i class="{{ $category->icon ?? 'fas fa-book' }}"></i>
                </div>
                <div class="category-name">{{ $category->localized_name }}</div>
                <div class="category-count">{{ $category->books_count }} {{ __('messages.books') }}</div>
            </a>
            @endforeach
        </div>
    </div>
</section>

<!-- Latest Books Section -->
<section class="section" style="padding-top: 2rem;">
    <div class="container">
        <div class="section-header">
            <h2 class="section-title">
                <span style="color: var(--orange-fluo); margin-right: 0.5rem;"><i class="fas fa-sparkles"></i></span>
                {{ __('messages.latest_books') }}
            </h2>
            <a href="{{ route('explore') }}?sort=latest" class="btn btn-ghost">
                {{ __('messages.view_all') }}
                <i class="fas fa-arrow-right"></i>
            </a>
        </div>

        <div class="grid-books">
            @foreach($latestBooks as $book)
            <a href="{{ route('books.show', $book) }}" class="card card-elevated book-card animate-on-scroll">
                <div class="book-cover">
                    <img src="{{ $book->cover_url }}" alt="{{ $book->title }}">
                    <div class="book-cover-overlay">
                        <span class="btn btn-primary btn-sm">{{ __('messages.read_now') }}</span>
                    </div>
                    <span class="badge badge-new" style="position: absolute; top: 10px; right: 10px; background: var(--orange-fluo); color: white; padding: 0.25rem 0.75rem; border-radius: 20px; font-size: 0.75rem; font-weight: bold;">NOUVEAU</span>
                </div>
                <div class="book-info">
                    <h3 class="book-title">{{ $book->title }}</h3>
                    <p class="book-author">{{ $book->author->name }}</p>
                    <div class="book-rating">
                        @for($i = 1; $i <= 5; $i++)
                            <i class="fas fa-star{{ $i <= $book->average_rating ? '' : '-o' }}"></i>
                        @endfor
                    </div>
                </div>
            </a>
            @endforeach
        </div>
    </div>
</section>

<!-- Featured Books Section -->
<section class="featured-section">
    <div class="container">
        <div class="section-header">
            <h2 class="section-title">{{ __('messages.featured_books') }}</h2>
            <a href="{{ route('explore') }}?sort=featured" class="btn btn-ghost">
                {{ __('messages.view_all') }}
                <i class="fas fa-arrow-right"></i>
            </a>
        </div>

        <div class="grid-books">
            @foreach($featuredBooks as $book)
            <a href="{{ route('books.show', $book) }}" class="card card-elevated book-card animate-on-scroll">
                <div class="book-cover">
                    <img src="{{ $book->cover_url }}" alt="{{ $book->title }}">
                    <div class="book-cover-overlay">
                        <span class="btn btn-primary btn-sm">{{ __('messages.read_now') }}</span>
                    </div>
                </div>
                <div class="book-info">
                    <h3 class="book-title">{{ $book->title }}</h3>
                    <p class="book-author">{{ $book->author->name }}</p>
                    <div class="book-rating">
                        @for($i = 1; $i <= 5; $i++)
                            <i class="fas fa-star{{ $i <= $book->average_rating ? '' : '-o' }}"></i>
                        @endfor
                        <span style="margin-left: 0.5rem; color: #64748b; font-size: 0.85rem;">
                            ({{ $book->reviews_count }})
                        </span>
                    </div>
                </div>
            </a>
            @endforeach
        </div>
    </div>
</section>

<!-- Premium CTA Section -->
<section class="premium-cta">
    <div class="container">
        <div class="premium-cta-card">
            <div class="premium-content">
                <div class="premium-badge">
                    <i class="fas fa-crown"></i>
                    {{ __('messages.premium') }}
                </div>
                <h2 class="premium-title">{{ __('messages.premium_title') }}</h2>
                <p class="premium-description">{{ __('messages.premium_description') }}</p>

                <ul class="premium-features">
                    <li>
                        <i class="fas fa-check-circle"></i>
                        {{ __('messages.premium_feature_1') }}
                    </li>
                    <li>
                        <i class="fas fa-check-circle"></i>
                        {{ __('messages.premium_feature_2') }}
                    </li>
                    <li>
                        <i class="fas fa-check-circle"></i>
                        {{ __('messages.premium_feature_3') }}
                    </li>
                    <li>
                        <i class="fas fa-check-circle"></i>
                        {{ __('messages.premium_feature_4') }}
                    </li>
                </ul>

                <a href="{{ route('pricing') }}" class="btn btn-primary btn-lg">
                    {{ __('messages.get_started') }}
                    <i class="fas fa-arrow-right"></i>
                </a>
            </div>

            <div class="premium-visual">
                <div class="pricing-preview">
                    <p class="pricing-from">{{ __('messages.starting_from') }}</p>
                    <div class="pricing-amount">3,000 <span>XAF</span></div>
                    <p class="pricing-period">/ {{ __('messages.month') }}</p>
                    <a href="{{ route('pricing') }}" class="btn btn-secondary">
                        {{ __('messages.see_all_plans') }}
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Scientific Reviews Section -->
@if($scientificReviews->count() > 0)
<section class="section">
    <div class="container">
        <div class="section-header">
            <h2 class="section-title">{{ __('messages.scientific_reviews') }}</h2>
            <a href="{{ route('explore') }}?type=scientific_review" class="btn btn-ghost">
                {{ __('messages.view_all') }}
                <i class="fas fa-arrow-right"></i>
            </a>
        </div>

        <div class="grid-books">
            @foreach($scientificReviews as $book)
            <a href="{{ route('books.show', $book) }}" class="card card-elevated book-card animate-on-scroll">
                <div class="book-cover">
                    <img src="{{ $book->cover_url }}" alt="{{ $book->title }}">
                </div>
                <div class="book-info">
                    <h3 class="book-title">{{ $book->title }}</h3>
                    <p class="book-author">{{ $book->author->name }}</p>
                </div>
            </a>
            @endforeach
        </div>
    </div>
</section>
@endif

<!-- Newsletter Section -->
<section class="newsletter-section">
    <div class="container">
        <div class="newsletter-card">
            <div class="newsletter-icon">
                <i class="fas fa-envelope"></i>
            </div>
            <h2 class="newsletter-title">{{ __('messages.newsletter_title') }}</h2>
            <p class="newsletter-description">{{ __('messages.newsletter_description') }}</p>

            <form action="{{ route('newsletter.subscribe') }}" method="POST" class="newsletter-form">
                @csrf
                <input type="email" name="email" class="newsletter-input" placeholder="{{ __('messages.enter_email') }}" required>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-paper-plane"></i>
                    {{ __('messages.subscribe') }}
                </button>
            </form>
        </div>
    </div>
</section>
@endsection
