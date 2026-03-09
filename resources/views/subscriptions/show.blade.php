@extends('layouts.app')
@section('title', __('messages.subscribe'))

@section('content')
<div class="section">
    <div class="container" style="max-width: 600px;">
        <h1 class="section-title" style="margin-bottom: 2rem; text-align: center;">{{ __('messages.confirm_subscription') }}</h1>

        <div class="card card-elevated" style="padding: 2rem; text-align: center;">
            <h2>{{ $plan->localized_name }}</h2>
            <div style="font-size: 2.5rem; font-weight: 800; color: var(--blue-roi); margin: 1rem 0;">
                {{ number_format($plan->price, 0, ',', ' ') }} XAF
            </div>
            <p style="color: #64748b; margin-bottom: 0.5rem;">{{ $plan->duration_label }} - {{ $plan->books_limit ?? $plan->publications_limit }} {{ $plan->isForReaders() ? __('messages.books') : __('messages.publications') }}</p>

            <form action="{{ route('subscribe.process', $plan->slug ?? $plan->id) }}" method="POST" style="margin-top: 1.5rem;">
                @csrf
                <button type="submit" class="btn btn-primary" style="width: 100%; font-size: 1.1rem;">
                    {{ __('messages.proceed_to_payment') }}
                </button>
            </form>
        </div>
    </div>
</div>
@endsection
