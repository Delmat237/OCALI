@extends('layouts.app')
@section('title', __('messages.withdraw'))

@section('content')
<div class="section">
    <div class="container" style="max-width: 600px;">
        <h1 class="section-title" style="margin-bottom: 2rem; text-align: center;">{{ __('messages.withdraw') }}</h1>

        <div class="card card-elevated" style="padding: 1.5rem; margin-bottom: 1.5rem; text-align: center;">
            <div style="color: #64748b;">{{ __('messages.available_balance') }}</div>
            <div style="font-size: 2rem; font-weight: 800; color: #10b981;">{{ $wallet->formatted_balance }}</div>
            <small style="color: #94a3b8;">{{ __('messages.min_withdrawal') }}: {{ number_format($minThreshold, 0, ',', ' ') }} XAF</small>
        </div>

        <div class="card card-elevated" style="padding: 2rem;">
            <form action="{{ route('author.wallet.withdraw.process') }}" method="POST">
                @csrf
                <div style="margin-bottom: 1rem;">
                    <label style="display: block; margin-bottom: 0.5rem; font-weight: 600;">{{ __('messages.amount') }} (XAF)</label>
                    <input type="number" name="amount" min="{{ $minThreshold }}" max="{{ $wallet->balance }}" required
                        style="width: 100%; padding: 0.75rem; border: 2px solid #e2e8f0; border-radius: 12px;">
                </div>
                <div style="margin-bottom: 1rem;">
                    <label style="display: block; margin-bottom: 0.5rem; font-weight: 600;">{{ __('messages.payment_method') }}</label>
                    <select name="payment_method" required style="width: 100%; padding: 0.75rem; border: 2px solid #e2e8f0; border-radius: 12px;">
                        <option value="orange">Orange Money</option>
                        <option value="mtn">MTN MoMo</option>
                    </select>
                </div>
                <div style="margin-bottom: 1.5rem;">
                    <label style="display: block; margin-bottom: 0.5rem; font-weight: 600;">{{ __('messages.phone_number') }}</label>
                    <input type="text" name="phone" placeholder="6XXXXXXXX" required
                        style="width: 100%; padding: 0.75rem; border: 2px solid #e2e8f0; border-radius: 12px;">
                </div>
                <button type="submit" class="btn btn-primary" style="width: 100%;">{{ __('messages.request_withdrawal') }}</button>
            </form>
        </div>
    </div>
</div>
@endsection
