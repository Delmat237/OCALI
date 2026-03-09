@extends('layouts.app')
@section('title', __('messages.transactions'))

@section('content')
<div class="section">
    <div class="container">
        <div class="section-header">
            <h1 class="section-title">{{ __('messages.transactions') }}</h1>
            <a href="{{ route('author.wallet') }}" class="btn btn-outline"><i class="fas fa-arrow-left"></i> {{ __('messages.back_to_wallet') }}</a>
        </div>

        @if($transactions->count() > 0)
            <div class="card card-elevated" style="overflow-x: auto;">
                <table style="width: 100%; border-collapse: collapse;">
                    <thead>
                        <tr style="border-bottom: 2px solid #e2e8f0;">
                            <th style="padding: 0.75rem 1rem; text-align: left;">{{ __('messages.date') }}</th>
                            <th style="padding: 0.75rem 1rem; text-align: left;">{{ __('messages.type') }}</th>
                            <th style="padding: 0.75rem 1rem; text-align: left;">{{ __('messages.description') }}</th>
                            <th style="padding: 0.75rem 1rem; text-align: right;">{{ __('messages.amount') }}</th>
                            <th style="padding: 0.75rem 1rem; text-align: right;">{{ __('messages.balance') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($transactions as $tx)
                            <tr style="border-bottom: 1px solid #f1f5f9;">
                                <td style="padding: 0.75rem 1rem;">{{ $tx->created_at->format('d/m/Y H:i') }}</td>
                                <td style="padding: 0.75rem 1rem;">{{ $tx->type_label }}</td>
                                <td style="padding: 0.75rem 1rem;">{{ $tx->description }}</td>
                                <td style="padding: 0.75rem 1rem; text-align: right; font-weight: 600; color: {{ $tx->isPositive() ? '#10b981' : '#ef4444' }};">{{ $tx->formatted_amount }}</td>
                                <td style="padding: 0.75rem 1rem; text-align: right;">{{ number_format($tx->balance_after, 0, ',', ' ') }} XAF</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            {{ $transactions->links() }}
        @else
            <p style="text-align: center; color: #94a3b8; padding: 2rem;">{{ __('messages.no_transactions') }}</p>
        @endif
    </div>
</div>
@endsection
