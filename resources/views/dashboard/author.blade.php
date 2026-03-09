@extends('layouts.app')
@section('title', __('messages.author_dashboard'))

@section('content')
<div class="section">
    <div class="container">
        <h1 class="section-title" style="margin-bottom: 2rem;">{{ __('messages.author_dashboard') }}</h1>

        <!-- Stats Cards -->
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); gap: 1.5rem; margin-bottom: 2rem;">
            <div class="card card-elevated" style="padding: 1.5rem; text-align: center;">
                <div style="font-size: 2rem; font-weight: 800; color: var(--blue-roi);">{{ $publishedBooks }}</div>
                <div style="color: #64748b;">{{ __('messages.published_books') }}</div>
            </div>
            <div class="card card-elevated" style="padding: 1.5rem; text-align: center;">
                <div style="font-size: 2rem; font-weight: 800; color: var(--orange-fluo);">{{ $pendingBooks }}</div>
                <div style="color: #64748b;">{{ __('messages.pending_books') }}</div>
            </div>
            <div class="card card-elevated" style="padding: 1.5rem; text-align: center;">
                <div style="font-size: 2rem; font-weight: 800; color: #10b981;">{{ number_format($totalReads) }}</div>
                <div style="color: #64748b;">{{ __('messages.total_reads') }}</div>
            </div>
            <div class="card card-elevated" style="padding: 1.5rem; text-align: center;">
                <div style="font-size: 2rem; font-weight: 800; color: #8b5cf6;">{{ number_format($totalViews) }}</div>
                <div style="color: #64748b;">{{ __('messages.total_views') }}</div>
            </div>
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 2rem;">
            <!-- Wallet -->
            <div class="card card-elevated" style="padding: 1.5rem;">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem;">
                    <h3>{{ __('messages.wallet') }}</h3>
                    <a href="{{ route('author.wallet') }}" class="btn btn-ghost" style="font-size: 0.85rem;">{{ __('messages.see_details') }}</a>
                </div>
                <div style="font-size: 2rem; font-weight: 800; color: #10b981; margin-bottom: 0.5rem;">
                    {{ $wallet->formatted_balance }}
                </div>
                <p style="color: #64748b; font-size: 0.9rem;">{{ __('messages.total_earned') }}: {{ number_format($wallet->total_earned, 0, ',', ' ') }} XAF</p>
                <div style="margin-top: 1rem; display: flex; gap: 0.75rem;">
                    <a href="{{ route('author.wallet.withdraw') }}" class="btn btn-primary" style="font-size: 0.85rem;">
                        <i class="fas fa-money-bill-wave"></i> {{ __('messages.withdraw') }}
                    </a>
                    <a href="{{ route('author.wallet.transactions') }}" class="btn btn-outline" style="font-size: 0.85rem;">
                        {{ __('messages.history') }}
                    </a>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="card card-elevated" style="padding: 1.5rem;">
                <h3 style="margin-bottom: 1rem;">{{ __('messages.quick_actions') }}</h3>
                <div style="display: flex; flex-direction: column; gap: 0.75rem;">
                    <a href="{{ route('author.books.create') }}" class="btn btn-primary" style="width: 100%;">
                        <i class="fas fa-plus"></i> {{ __('messages.publish_book') }}
                    </a>
                    <a href="{{ route('author.books.index') }}" class="btn btn-outline" style="width: 100%;">
                        <i class="fas fa-book"></i> {{ __('messages.my_books') }}
                    </a>
                    <a href="{{ route('author.chronicles.create') }}" class="btn btn-secondary" style="width: 100%;">
                        <i class="fas fa-pen"></i> {{ __('messages.write_chronicle') }}
                    </a>
                    <a href="{{ route('author.chronicles.index') }}" class="btn btn-outline" style="width: 100%;">
                        <i class="fas fa-scroll"></i> {{ __('messages.my_chronicles') }}
                    </a>
                    <a href="{{ route('author.statistics') }}" class="btn btn-outline" style="width: 100%;">
                        <i class="fas fa-chart-bar"></i> {{ __('messages.view_statistics') }}
                    </a>
                    <a href="{{ route('profile') }}" class="btn btn-ghost" style="width: 100%;">
                        <i class="fas fa-user"></i> {{ __('messages.profile') }}
                    </a>
                </div>
            </div>
        </div>

        <!-- My Books -->
        <div style="margin-top: 2rem;">
            <div class="section-header">
                <h2 class="section-title">{{ __('messages.my_books') }}</h2>
                <a href="{{ route('author.books.index') }}" class="btn btn-ghost">{{ __('messages.see_all') }}</a>
            </div>
            @if($myBooks->count() > 0)
                <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(250px, 1fr)); gap: 1rem;">
                    @foreach($myBooks as $book)
                        <div class="card card-elevated" style="display: flex; gap: 1rem; padding: 1rem;">
                            <img src="{{ $book->cover_url }}" alt="" style="width: 60px; height: 85px; object-fit: cover; border-radius: 8px;">
                            <div style="flex: 1;">
                                <h4 style="font-size: 0.9rem;">{{ $book->title }}</h4>
                                <span style="font-size: 0.75rem; padding: 0.15rem 0.5rem; border-radius: 10px; background: {{ $book->status === 'approved' ? 'rgba(16,185,129,0.1)' : ($book->status === 'pending' ? 'rgba(245,158,11,0.1)' : 'rgba(239,68,68,0.1)') }}; color: {{ $book->status === 'approved' ? '#059669' : ($book->status === 'pending' ? '#d97706' : '#dc2626') }};">{{ $book->status_label }}</span>
                                <p style="font-size: 0.8rem; color: #64748b; margin-top: 0.25rem;">{{ $book->reads_count }} {{ __('messages.reads') }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <p style="color: #94a3b8; text-align: center; padding: 2rem;">{{ __('messages.no_books_yet') }}</p>
            @endif
        </div>

        <!-- Recent Transactions -->
        @if($recentTransactions->count() > 0)
            <div style="margin-top: 2rem;">
                <h2 class="section-title" style="margin-bottom: 1.5rem;">{{ __('messages.recent_transactions') }}</h2>
                <div class="card card-elevated" style="overflow-x: auto;">
                    <table style="width: 100%; border-collapse: collapse;">
                        <thead>
                            <tr style="border-bottom: 2px solid #e2e8f0;">
                                <th style="padding: 0.75rem 1rem; text-align: left; font-size: 0.85rem; color: #64748b;">{{ __('messages.date') }}</th>
                                <th style="padding: 0.75rem 1rem; text-align: left; font-size: 0.85rem; color: #64748b;">{{ __('messages.description') }}</th>
                                <th style="padding: 0.75rem 1rem; text-align: right; font-size: 0.85rem; color: #64748b;">{{ __('messages.amount') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($recentTransactions as $tx)
                                <tr style="border-bottom: 1px solid #f1f5f9;">
                                    <td style="padding: 0.75rem 1rem; font-size: 0.9rem;">{{ $tx->created_at->format('d/m/Y') }}</td>
                                    <td style="padding: 0.75rem 1rem; font-size: 0.9rem;">{{ $tx->description }}</td>
                                    <td style="padding: 0.75rem 1rem; text-align: right; font-weight: 600; color: {{ $tx->isPositive() ? '#10b981' : '#ef4444' }};">{{ $tx->formatted_amount }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @endif
    </div>
</div>
@endsection
