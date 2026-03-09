@extends('layouts.app')
@section('title', __('messages.withdrawals'))

@section('content')
<div class="section">
    <div class="container">
        <h1 class="section-title" style="margin-bottom: 2rem;">{{ __('messages.withdrawals') }}</h1>

        <div class="card card-elevated" style="overflow-x: auto;">
            <table style="width: 100%; border-collapse: collapse;">
                <thead>
                    <tr style="border-bottom: 2px solid #e2e8f0;">
                        <th style="padding: 0.75rem 1rem; text-align: left;">{{ __('messages.author') }}</th>
                        <th style="padding: 0.75rem 1rem; text-align: right;">{{ __('messages.amount') }}</th>
                        <th style="padding: 0.75rem 1rem; text-align: center;">{{ __('messages.method') }}</th>
                        <th style="padding: 0.75rem 1rem; text-align: center;">{{ __('messages.phone') }}</th>
                        <th style="padding: 0.75rem 1rem; text-align: center;">{{ __('messages.status') }}</th>
                        <th style="padding: 0.75rem 1rem; text-align: center;">{{ __('messages.date') }}</th>
                        <th style="padding: 0.75rem 1rem; text-align: center;">{{ __('messages.actions') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($withdrawals as $w)
                        <tr style="border-bottom: 1px solid #f1f5f9;">
                            <td style="padding: 0.75rem 1rem;">{{ $w->user->name ?? '-' }}</td>
                            <td style="padding: 0.75rem 1rem; text-align: right; font-weight: 600;">{{ number_format($w->amount, 0, ',', ' ') }} XAF</td>
                            <td style="padding: 0.75rem 1rem; text-align: center;">{{ strtoupper($w->payment_method) }}</td>
                            <td style="padding: 0.75rem 1rem; text-align: center;">{{ $w->phone }}</td>
                            <td style="padding: 0.75rem 1rem; text-align: center;">
                                <span style="padding: 0.2rem 0.5rem; border-radius: 10px; font-size: 0.8rem; background: {{ $w->status === 'approved' ? 'rgba(16,185,129,0.1)' : ($w->status === 'pending' ? 'rgba(245,158,11,0.1)' : 'rgba(239,68,68,0.1)') }}; color: {{ $w->status === 'approved' ? '#059669' : ($w->status === 'pending' ? '#d97706' : '#dc2626') }};">{{ $w->status }}</span>
                            </td>
                            <td style="padding: 0.75rem 1rem; text-align: center;">{{ $w->created_at->format('d/m/Y') }}</td>
                            <td style="padding: 0.75rem 1rem; text-align: center;">
                                @if($w->status === 'pending')
                                    <div style="display: flex; gap: 0.25rem; justify-content: center;">
                                        <form action="{{ route('admin.withdrawals.approve', $w) }}" method="POST">@csrf <button class="btn btn-ghost btn-icon" style="color: #10b981;"><i class="fas fa-check"></i></button></form>
                                        <form action="{{ route('admin.withdrawals.reject', $w) }}" method="POST">@csrf <button class="btn btn-ghost btn-icon" style="color: #ef4444;"><i class="fas fa-times"></i></button></form>
                                    </div>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        {{ $withdrawals->links() }}
    </div>
</div>
@endsection
