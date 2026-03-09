@extends('layouts.app')
@section('title', __('messages.wallet'))

@section('content')
<div class="section">
    <div class="container">
        <h1 class="section-title" style="margin-bottom: 2rem;">{{ __('messages.wallet') }}</h1>

        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1.5rem; margin-bottom: 2rem;">
            <div class="card card-elevated" style="padding: 1.5rem; text-align: center; background: linear-gradient(135deg, rgba(16,185,129,0.05), rgba(16,185,129,0.1));">
                <div style="font-size: 2rem; font-weight: 800; color: #10b981;">{{ $wallet->formatted_balance }}</div>
                <div style="color: #64748b;">{{ __('messages.available_balance') }}</div>
            </div>
            <div class="card card-elevated" style="padding: 1.5rem; text-align: center;">
                <div style="font-size: 2rem; font-weight: 800; color: var(--blue-roi);">{{ number_format($wallet->total_earned, 0, ',', ' ') }} XAF</div>
                <div style="color: #64748b;">{{ __('messages.total_earned') }}</div>
            </div>
            <div class="card card-elevated" style="padding: 1.5rem; text-align: center;">
                <div style="font-size: 2rem; font-weight: 800; color: var(--orange-fluo);">{{ number_format($wallet->total_withdrawn, 0, ',', ' ') }} XAF</div>
                <div style="color: #64748b;">{{ __('messages.total_withdrawn') }}</div>
            </div>
        </div>

        <div style="display: flex; gap: 1rem; margin-bottom: 2rem;">
            <a href="{{ route('author.wallet.withdraw') }}" class="btn btn-primary"><i class="fas fa-money-bill-wave"></i> {{ __('messages.withdraw') }}</a>
            <a href="{{ route('author.wallet.transactions') }}" class="btn btn-outline">{{ __('messages.all_transactions') }}</a>
        </div>

        <p style="color: #64748b; font-size: 0.85rem; margin-bottom: 2rem;">
            <i class="fas fa-info-circle"></i> {{ __('messages.min_withdrawal_info', ['amount' => number_format($minThreshold, 0, ',', ' ')]) }}
        </p>

        @if($recentTransactions->count() > 0)
            <h2 style="font-size: 1.3rem; margin-bottom: 1rem;">{{ __('messages.recent_transactions') }}</h2>
            <div class="card card-elevated" style="overflow-x: auto;">
                <table style="width: 100%; border-collapse: collapse;">
                    <thead>
                        <tr style="border-bottom: 2px solid #e2e8f0;">
                            <th style="padding: 0.75rem 1rem; text-align: left;">{{ __('messages.date') }}</th>
                            <th style="padding: 0.75rem 1rem; text-align: left;">{{ __('messages.type') }}</th>
                            <th style="padding: 0.75rem 1rem; text-align: left;">{{ __('messages.description') }}</th>
                            <th style="padding: 0.75rem 1rem; text-align: right;">{{ __('messages.amount') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($recentTransactions as $tx)
                            <tr style="border-bottom: 1px solid #f1f5f9;">
                                <td style="padding: 0.75rem 1rem;">{{ $tx->created_at->format('d/m/Y H:i') }}</td>
                                <td style="padding: 0.75rem 1rem;"><span style="color: {{ $tx->type_color === 'green' ? '#10b981' : '#ef4444' }};">{{ $tx->type_label }}</span></td>
                                <td style="padding: 0.75rem 1rem;">{{ $tx->description }}</td>
                                <td style="padding: 0.75rem 1rem; text-align: right; font-weight: 600; color: {{ $tx->isPositive() ? '#10b981' : '#ef4444' }};">{{ $tx->formatted_amount }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
</div>
@endsection
