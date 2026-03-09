@extends('layouts.app')
@section('title', __('messages.forgot_password'))

@section('content')
<div class="section" style="display: flex; align-items: center; min-height: calc(100vh - 70px);">
    <div class="container" style="max-width: 450px;">
        <div class="card card-elevated" style="padding: 2.5rem;">
            <h2 style="text-align: center; margin-bottom: 0.5rem;">{{ __('messages.forgot_password') }}</h2>
            <p style="text-align: center; color: #64748b; margin-bottom: 1.5rem; font-size: 0.9rem;">{{ __('messages.forgot_password_text') }}</p>

            @if(session('status'))
                <div class="alert alert-success"><i class="fas fa-check-circle"></i> {{ session('status') }}</div>
            @endif

            <form action="{{ route('password.email') }}" method="POST">
                @csrf
                <div style="margin-bottom: 1.5rem;">
                    <label style="display: block; margin-bottom: 0.5rem; font-weight: 600;">{{ __('messages.email') }}</label>
                    <input type="email" name="email" value="{{ old('email') }}" required autofocus
                        style="width: 100%; padding: 0.75rem; border: 2px solid #e2e8f0; border-radius: 12px;">
                    @error('email') <small style="color: #ef4444;">{{ $message }}</small> @enderror
                </div>
                <button type="submit" class="btn btn-primary" style="width: 100%;">{{ __('messages.send_reset_link') }}</button>
            </form>

            <p style="text-align: center; margin-top: 1rem; font-size: 0.9rem;">
                <a href="{{ route('login') }}" style="color: var(--blue-roi);">{{ __('messages.back_to_login') }}</a>
            </p>
        </div>
    </div>
</div>
@endsection
