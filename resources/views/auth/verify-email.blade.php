@extends('layouts.app')
@section('title', __('messages.verify_email'))

@section('content')
<div class="section" style="display: flex; align-items: center; min-height: calc(100vh - 70px);">
    <div class="container" style="max-width: 500px; text-align: center;">
        <div class="card card-elevated" style="padding: 3rem;">
            <i class="fas fa-envelope-open-text" style="font-size: 4rem; color: var(--blue-roi); margin-bottom: 1rem;"></i>
            <h2>{{ __('messages.verify_email') }}</h2>

            {{-- Highlight the destination email --}}
            <div style="
                display: inline-flex; align-items: center; gap: .5rem;
                background: rgba(65,105,225,.08); border: 1.5px solid rgba(65,105,225,.2);
                border-radius: 99px; padding: .45rem 1.1rem; margin: .75rem 0 .5rem;
                font-weight: 700; color: var(--blue-roi); font-size: .95rem;
            ">
                <i class="fas fa-at"></i>
                {{ Auth::user()->email }}
            </div>

            <p style="color: #64748b; margin: .5rem 0 1.25rem;">{{ __('messages.verify_email_text') }}</p>

            @if(session('status') === 'verification-link-sent')
                <div class="alert alert-success"><i class="fas fa-check-circle"></i> {{ __('messages.verification_link_sent') }}</div>
            @endif

            <form action="{{ route('verification.send') }}" method="POST">
                @csrf
                <button type="submit" class="btn btn-primary">{{ __('messages.resend_verification') }}</button>
            </form>

            <form action="{{ route('logout') }}" method="POST" style="margin-top: 1rem;">
                @csrf
                <button type="submit" class="btn btn-ghost">{{ __('messages.logout') }}</button>
            </form>
        </div>
    </div>
</div>
@endsection
