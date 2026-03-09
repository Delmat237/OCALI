@extends('layouts.app')
@section('title', __('messages.subscriptions'))

@section('content')
<div class="section">
    <div class="container">
        <h1 class="section-title" style="margin-bottom: 2rem;">{{ __('messages.subscriptions') }}</h1>
        <div class="card card-elevated" style="overflow-x: auto;">
            <table style="width: 100%; border-collapse: collapse;">
                <thead>
                    <tr style="border-bottom: 2px solid #e2e8f0;">
                        <th style="padding: 0.75rem 1rem; text-align: left;">{{ __('messages.user') }}</th>
                        <th style="padding: 0.75rem 1rem; text-align: left;">{{ __('messages.plan') }}</th>
                        <th style="padding: 0.75rem 1rem; text-align: right;">{{ __('messages.amount') }}</th>
                        <th style="padding: 0.75rem 1rem; text-align: center;">{{ __('messages.status') }}</th>
                        <th style="padding: 0.75rem 1rem; text-align: center;">{{ __('messages.expires') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($subscriptions as $sub)
                        <tr style="border-bottom: 1px solid #f1f5f9;">
                            <td style="padding: 0.75rem 1rem;">{{ $sub->user->name ?? '-' }}</td>
                            <td style="padding: 0.75rem 1rem;">{{ $sub->plan->localized_name ?? '-' }}</td>
                            <td style="padding: 0.75rem 1rem; text-align: right;">{{ number_format($sub->amount_paid, 0, ',', ' ') }} XAF</td>
                            <td style="padding: 0.75rem 1rem; text-align: center;">
                                <span style="color: {{ $sub->status_color }};">{{ $sub->status_label }}</span>
                            </td>
                            <td style="padding: 0.75rem 1rem; text-align: center;">{{ $sub->ends_at?->format('d/m/Y') ?? '-' }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        {{ $subscriptions->links() }}
    </div>
</div>
@endsection
