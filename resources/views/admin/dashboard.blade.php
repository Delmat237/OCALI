@extends('layouts.app')
@section('title', __('messages.admin_dashboard'))

@section('content')
<div class="section">
    <div class="container">
        <h1 class="section-title" style="margin-bottom: 2rem;">{{ __('messages.admin_dashboard') }}</h1>

        <!-- Stats -->
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); gap: 1rem; margin-bottom: 2rem;">
            <div class="card card-elevated" style="padding: 1.25rem; text-align: center;">
                <i class="fas fa-users" style="font-size: 1.5rem; color: var(--blue-roi); margin-bottom: 0.5rem;"></i>
                <div style="font-size: 1.8rem; font-weight: 800;">{{ number_format($stats['total_users']) }}</div>
                <div style="color: #64748b; font-size: 0.85rem;">{{ __('messages.total_users') }}</div>
            </div>
            <div class="card card-elevated" style="padding: 1.25rem; text-align: center;">
                <i class="fas fa-book" style="font-size: 1.5rem; color: var(--orange-fluo); margin-bottom: 0.5rem;"></i>
                <div style="font-size: 1.8rem; font-weight: 800;">{{ number_format($stats['total_books']) }}</div>
                <div style="color: #64748b; font-size: 0.85rem;">{{ __('messages.total_books') }}</div>
            </div>
            <div class="card card-elevated" style="padding: 1.25rem; text-align: center;">
                <i class="fas fa-clock" style="font-size: 1.5rem; color: #f59e0b; margin-bottom: 0.5rem;"></i>
                <div style="font-size: 1.8rem; font-weight: 800;">{{ $stats['pending_books'] }}</div>
                <div style="color: #64748b; font-size: 0.85rem;">{{ __('messages.pending_approval') }}</div>
            </div>
            <div class="card card-elevated" style="padding: 1.25rem; text-align: center;">
                <i class="fas fa-crown" style="font-size: 1.5rem; color: #10b981; margin-bottom: 0.5rem;"></i>
                <div style="font-size: 1.8rem; font-weight: 800;">{{ number_format($stats['active_subscriptions']) }}</div>
                <div style="color: #64748b; font-size: 0.85rem;">{{ __('messages.active_subscriptions') }}</div>
            </div>
            <div class="card card-elevated" style="padding: 1.25rem; text-align: center;">
                <i class="fas fa-money-bill-wave" style="font-size: 1.5rem; color: #8b5cf6; margin-bottom: 0.5rem;"></i>
                <div style="font-size: 1.8rem; font-weight: 800;">{{ number_format($stats['total_revenue'], 0, ',', ' ') }}</div>
                <div style="color: #64748b; font-size: 0.85rem;">{{ __('messages.revenue') }} (XAF)</div>
            </div>
            <div class="card card-elevated" style="padding: 1.25rem; text-align: center;">
                <i class="fas fa-exclamation-triangle" style="font-size: 1.5rem; color: #ef4444; margin-bottom: 0.5rem;"></i>
                <div style="font-size: 1.8rem; font-weight: 800;">{{ $stats['pending_reports'] }}</div>
                <div style="color: #64748b; font-size: 0.85rem;">{{ __('messages.pending_reports') }}</div>
            </div>
        </div>

        <!-- Quick Links -->
        <div style="margin-bottom: 2rem;">
            <h3 style="margin-bottom: 1rem;">{{ __('messages.quick_actions') }}</h3>
            
            <!-- Priority Actions -->
            <div style="display: flex; flex-wrap: wrap; gap: 0.75rem; margin-bottom: 1.5rem;">
                <a href="{{ route('admin.books.pending') }}" class="btn btn-primary"><i class="fas fa-check"></i> {{ __('messages.approve_books') }} ({{ $stats['pending_books'] }})</a>
                <a href="{{ route('admin.withdrawals.index') }}" class="btn btn-secondary"><i class="fas fa-wallet"></i> {{ __('messages.withdrawals') }} ({{ $stats['pending_withdrawals'] }})</a>
                <a href="{{ route('admin.reports.index') }}" class="btn btn-outline"><i class="fas fa-flag"></i> {{ __('messages.reports') }} ({{ $stats['pending_reports'] }})</a>
            </div>

            <!-- Management Actions -->
            <div class="card card-elevated" style="padding: 1.5rem;">
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem;">
                    <!-- User Management -->
                    <a href="{{ route('admin.users.index') }}" class="btn btn-outline" style="width: 100%;">
                        <i class="fas fa-users"></i> {{ __('messages.manage_users') }}
                    </a>
                    
                    <!-- Book Management -->
                    <a href="{{ route('admin.books.index') }}" class="btn btn-outline" style="width: 100%;">
                        <i class="fas fa-book"></i> {{ __('messages.manage_books') }}
                    </a>
                    
                    <!-- Subscription Management -->
                    <a href="{{ route('admin.subscriptions.index') }}" class="btn btn-outline" style="width: 100%;">
                        <i class="fas fa-crown"></i> {{ __('messages.subscriptions') }}
                    </a>
                    
                    <!-- Plan Management -->
                    <a href="{{ route('admin.plans.index') }}" class="btn btn-outline" style="width: 100%;">
                        <i class="fas fa-tags"></i> {{ __('messages.plans') }}
                    </a>
                    
                    <!-- Category Management -->
                    <a href="{{ route('admin.categories.index') }}" class="btn btn-outline" style="width: 100%;">
                        <i class="fas fa-folder"></i> {{ __('messages.categories') }}
                    </a>
                    
                    <!-- Newsletter -->
                    <a href="{{ route('admin.newsletter.index') }}" class="btn btn-outline" style="width: 100%;">
                        <i class="fas fa-envelope"></i> {{ __('messages.newsletter') }}
                    </a>
                    
                    <!-- Statistics -->
                    <a href="{{ route('admin.statistics') }}" class="btn btn-outline" style="width: 100%;">
                        <i class="fas fa-chart-bar"></i> {{ __('messages.statistics') }}
                    </a>
                    
                    <!-- Financial Reports -->
                    <a href="{{ route('admin.financial-reports') }}" class="btn btn-outline" style="width: 100%;">
                        <i class="fas fa-money-bill-wave"></i> {{ __('messages.financial_reports') }}
                    </a>
                    
                    <!-- Settings -->
                    <a href="{{ route('admin.settings') }}" class="btn btn-ghost" style="width: 100%;">
                        <i class="fas fa-cog"></i> {{ __('messages.settings') }}
                    </a>
                </div>
            </div>
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 2rem;">
            <!-- Recent Users -->
            <div class="card card-elevated" style="padding: 1.5rem;">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem;">
                    <h3>{{ __('messages.recent_users') }}</h3>
                    <a href="{{ route('admin.users.index') }}" class="btn btn-ghost" style="font-size: 0.85rem;">{{ __('messages.see_all') }}</a>
                </div>
                @foreach($recentUsers as $user)
                    <div style="display: flex; align-items: center; gap: 0.75rem; padding: 0.5rem 0; border-bottom: 1px solid #f1f5f9;">
                        <img src="{{ $user->avatar_url }}" alt="" style="width: 32px; height: 32px; border-radius: 50%;">
                        <div style="flex: 1;">
                            <div style="font-weight: 600; font-size: 0.9rem;">{{ $user->name }}</div>
                            <div style="font-size: 0.8rem; color: #64748b;">{{ $user->email }}</div>
                        </div>
                        <span style="font-size: 0.75rem; padding: 0.15rem 0.5rem; border-radius: 10px; background: rgba(65,105,225,0.1); color: var(--blue-roi);">{{ $user->role_label }}</span>
                    </div>
                @endforeach
            </div>

            <!-- Recent Books -->
            <div class="card card-elevated" style="padding: 1.5rem;">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem;">
                    <h3>{{ __('messages.recent_books') }}</h3>
                    <a href="{{ route('admin.books.index') }}" class="btn btn-ghost" style="font-size: 0.85rem;">{{ __('messages.see_all') }}</a>
                </div>
                @foreach($recentBooks as $book)
                    <div style="display: flex; align-items: center; gap: 0.75rem; padding: 0.5rem 0; border-bottom: 1px solid #f1f5f9;">
                        <img src="{{ $book->cover_url }}" alt="" style="width: 32px; height: 45px; object-fit: cover; border-radius: 4px;">
                        <div style="flex: 1;">
                            <div style="font-weight: 600; font-size: 0.9rem;">{{ $book->title }}</div>
                            <div style="font-size: 0.8rem; color: #64748b;">{{ $book->author->name ?? '' }}</div>
                        </div>
                        <span style="font-size: 0.75rem; padding: 0.15rem 0.5rem; border-radius: 10px; background: {{ $book->status === 'approved' ? 'rgba(16,185,129,0.1)' : 'rgba(245,158,11,0.1)' }}; color: {{ $book->status === 'approved' ? '#059669' : '#d97706' }};">{{ $book->status_label }}</span>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
@endsection
