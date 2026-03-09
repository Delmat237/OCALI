@extends('layouts.app')
@section('title', __('messages.reset_password'))

@section('content')
<div class="section" style="display: flex; align-items: center; min-height: calc(100vh - 70px);">
    <div class="container" style="max-width: 450px;">
        <div class="card card-elevated" style="padding: 2.5rem;">
            <h2 style="text-align: center; margin-bottom: 1.5rem;">{{ __('messages.reset_password') }}</h2>
            <form action="{{ route('password.update') }}" method="POST">
                @csrf
                <input type="hidden" name="token" value="{{ $token }}">
                <div style="margin-bottom: 1rem;">
                    <label style="display: block; margin-bottom: 0.5rem; font-weight: 600;">{{ __('messages.email') }}</label>
                    <input type="email" name="email" value="{{ old('email') }}" required style="width: 100%; padding: 0.75rem; border: 2px solid #e2e8f0; border-radius: 12px;">
                </div>
                <div style="margin-bottom: 1rem;">
                    <label style="display: block; margin-bottom: 0.5rem; font-weight: 600;">{{ __('messages.new_password') }}</label>
                    <input type="password" name="password" required style="width: 100%; padding: 0.75rem; border: 2px solid #e2e8f0; border-radius: 12px;">
                </div>
                <div style="margin-bottom: 1.5rem;">
                    <label style="display: block; margin-bottom: 0.5rem; font-weight: 600;">{{ __('messages.confirm_password') }}</label>
                    <input type="password" name="password_confirmation" required style="width: 100%; padding: 0.75rem; border: 2px solid #e2e8f0; border-radius: 12px;">
                </div>
                <button type="submit" class="btn btn-primary" style="width: 100%;">{{ __('messages.reset_password') }}</button>
            </form>
        </div>
    </div>
</div>
@endsection
