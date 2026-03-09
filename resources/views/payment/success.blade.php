@extends('layouts.app')

@section('title', __('messages.payment_success'))

@section('content')
<div class="section">
    <div class="container" style="max-width: 500px; text-align: center;">
        <div class="card card-elevated" style="padding: 3rem;">
            <div style="font-size: 4rem; color: #10b981; margin-bottom: 1rem;">
                <i class="fas fa-check-circle"></i>
            </div>
            <h2 style="margin-bottom: 0.5rem; color: #10b981;">{{ __('messages.payment_success') }}</h2>
            <p style="color: #64748b; margin-bottom: 1.5rem;">{{ __('messages.payment_success_msg') }}</p>

            <a href="{{ route('dashboard') }}" class="btn btn-primary" style="width: 100%;">
                {{ __('messages.go_to_dashboard') }}
            </a>
        </div>
    </div>
</div>
@endsection
