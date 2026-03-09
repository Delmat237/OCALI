@extends('layouts.app')
@section('title', __('messages.unsubscribed'))

@section('content')
<div class="section" style="display: flex; align-items: center; min-height: calc(100vh - 70px);">
    <div class="container" style="max-width: 500px; text-align: center;">
        <div class="card card-elevated" style="padding: 3rem;">
            <i class="fas fa-envelope-open" style="font-size: 4rem; color: #64748b; margin-bottom: 1rem;"></i>
            <h2>{{ __('messages.unsubscribed') }}</h2>
            <p style="color: #64748b; margin: 1rem 0;">{{ __('messages.unsubscribed_text') }}</p>
            <a href="{{ route('home') }}" class="btn btn-primary">{{ __('messages.back_to_home') }}</a>
        </div>
    </div>
</div>
@endsection
