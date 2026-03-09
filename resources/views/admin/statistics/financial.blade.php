@extends('layouts.app')
@section('title', __('messages.financial_reports'))

@section('content')
<div class="section">
    <div class="container">
        <h1 class="section-title" style="margin-bottom: 2rem;">{{ __('messages.financial_reports') }}</h1>

        <div style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 1.5rem; margin-bottom: 2rem;">
            <div class="card card-elevated" style="padding: 1.5rem; text-align: center;">
                <div style="font-size: 1.8rem; font-weight: 800; color: #10b981;">{{ number_format($totalRevenue, 0, ',', ' ') }}</div>
                <div style="color: #64748b;">{{ __('messages.total_revenue') }} (XAF)</div>
            </div>
            <div class="card card-elevated" style="padding: 1.5rem; text-align: center;">
                <div style="font-size: 1.8rem; font-weight: 800; color: var(--blue-roi);">{{ number_format($totalCommissions, 0, ',', ' ') }}</div>
                <div style="color: #64748b;">{{ __('messages.commissions') }} (XAF)</div>
            </div>
            <div class="card card-elevated" style="padding: 1.5rem; text-align: center;">
                <div style="font-size: 1.8rem; font-weight: 800; color: var(--orange-fluo);">{{ number_format($totalWithdrawals, 0, ',', ' ') }}</div>
                <div style="color: #64748b;">{{ __('messages.total_withdrawals') }} (XAF)</div>
            </div>
            <div class="card card-elevated" style="padding: 1.5rem; text-align: center;">
                <div style="font-size: 1.8rem; font-weight: 800; color: #f59e0b;">{{ number_format($pendingWithdrawals, 0, ',', ' ') }}</div>
                <div style="color: #64748b;">{{ __('messages.pending') }} (XAF)</div>
            </div>
        </div>
    </div>
</div>
@endsection
