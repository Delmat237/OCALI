@extends('layouts.app')
@section('title', __('messages.newsletter'))

@section('content')
<div class="section">
    <div class="container" style="max-width: 800px;">
        <h1 class="section-title" style="margin-bottom: 2rem;">{{ __('messages.newsletter') }}</h1>

        <div class="card card-elevated" style="padding: 1.5rem; margin-bottom: 2rem; text-align: center;">
            <div style="font-size: 2rem; font-weight: 800; color: var(--blue-roi);">{{ $totalSubscribers }}</div>
            <div style="color: #64748b;">{{ __('messages.active_subscribers') }}</div>
        </div>

        <div class="card card-elevated" style="padding: 2rem; margin-bottom: 2rem;">
            <h3 style="margin-bottom: 1rem;">{{ __('messages.send_newsletter') }}</h3>
            <form action="{{ route('admin.newsletter.send') }}" method="POST">
                @csrf
                <div style="margin-bottom: 1rem;">
                    <label style="display: block; margin-bottom: 0.5rem; font-weight: 600;">{{ __('messages.subject') }}</label>
                    <input type="text" name="subject" required style="width: 100%; padding: 0.75rem; border: 2px solid #e2e8f0; border-radius: 12px;">
                </div>
                <div style="margin-bottom: 1.5rem;">
                    <label style="display: block; margin-bottom: 0.5rem; font-weight: 600;">{{ __('messages.content') }}</label>
                    <textarea name="content" rows="10" required style="width: 100%; padding: 0.75rem; border: 2px solid #e2e8f0; border-radius: 12px; resize: vertical;"></textarea>
                </div>
                <button type="submit" class="btn btn-primary" onclick="return confirm('{{ __('messages.confirm_send_newsletter') }}')">
                    <i class="fas fa-paper-plane"></i> {{ __('messages.send_to_all') }}
                </button>
            </form>
        </div>
    </div>
</div>
@endsection
