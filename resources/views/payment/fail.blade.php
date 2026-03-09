@extends('layouts.app')

@section('title', __('messages.payment_failed'))

@section('content')
<div class="section">
    <div class="container" style="max-width: 500px; text-align: center;">
        <div class="card card-elevated" style="padding: 3rem;">
            <div style="font-size: 4rem; color: #ef4444; margin-bottom: 1rem;">
                <i class="fas fa-times-circle"></i>
            </div>
            <h2 style="margin-bottom: 0.5rem; color: #ef4444;">{{ __('messages.payment_failed') }}</h2>
            <p style="color: #64748b; margin-bottom: 1.5rem;">{{ __('messages.payment_failed_msg') }}</p>

            <a href="{{ route('pricing') }}" class="btn btn-primary" style="width: 100%;">
                {{ __('messages.try_again') }}
            </a>
            <a href="{{ route('dashboard') }}" class="btn btn-ghost" style="margin-top: 1rem;">
                {{ __('messages.back_to_dashboard') }}
            </a>
        </div>
    </div>
</div>
@endsection
