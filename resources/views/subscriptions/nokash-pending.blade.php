@extends('layouts.app')
@section('title', __('messages.payment_pending'))

@section('content')
<div class="section">
    <div class="container" style="max-width: 500px; text-align: center;">
        <div class="card card-elevated" style="padding: 3rem;">
            <div style="font-size: 4rem; color: var(--orange-fluo); margin-bottom: 1rem;">
                <i class="fas fa-clock"></i>
            </div>
            <h2 style="margin-bottom: 0.5rem;">{{ __('messages.payment_pending') }}</h2>
            <p style="color: #64748b; margin-bottom: 1.5rem;">{{ __('messages.payment_pending_text') }}</p>

            <form action="{{ route('payment.nokash.success', $subscription) }}" method="POST">
                @csrf
                <div style="margin-bottom: 1rem;">
                    <input type="text" name="reference" placeholder="{{ __('messages.enter_reference') }}" required
                        style="width: 100%; padding: 0.75rem; border: 2px solid #e2e8f0; border-radius: 12px; text-align: center;">
                </div>
                <button type="submit" class="btn btn-primary" style="width: 100%;">{{ __('messages.confirm_payment') }}</button>
            </form>

            <a href="{{ route('dashboard') }}" class="btn btn-ghost" style="margin-top: 1rem;">{{ __('messages.back_to_dashboard') }}</a>
        </div>
    </div>
</div>
@endsection
