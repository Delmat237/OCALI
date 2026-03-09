@extends('layouts.app')
@section('title', __('messages.pricing'))

@section('content')
<div class="section">
    <div class="container" style="text-align: center;">
        <h1 style="font-size: 2.5rem; margin-bottom: 0.5rem;">{{ __('messages.choose_plan') }}</h1>
        <p style="color: #64748b; max-width: 600px; margin: 0 auto 3rem;">{{ __('messages.pricing_subtitle') }}</p>

        <!-- Reader Plans -->
        <h2 class="section-title" style="margin-bottom: 2rem;">{{ __('messages.reader_plans') }}</h2>
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 2rem; margin-bottom: 4rem; max-width: 960px; margin-left: auto; margin-right: auto;">
            @foreach($readerPlans as $plan)
                <div class="card card-elevated" style="padding: 2rem; position: relative; {{ $plan->is_popular ? 'border: 2px solid var(--orange-fluo);' : '' }}">
                    @if($plan->is_popular)
                        <div style="position: absolute; top: 5px; left: 50%; transform: translateX(-50%); background: var(--gradient-primary); color: white; padding: 0.25rem 1rem; border-radius: 20px; font-size: 0.8rem; font-weight: 600;">{{ __('messages.popular') }}</div>
                    @endif
                    <h3 style="font-size: 1.3rem; margin-bottom: 0.5rem;">{{ $plan->localized_name }}</h3>
                    <div style="margin-bottom: 1rem;">
                        <span style="font-size: 2.5rem; font-weight: 800; color: var(--blue-roi);">{{ number_format($plan->price, 0, ',', ' ') }}</span>
                        <span style="color: #64748b;"> XAF/{{ $plan->duration_label }}</span>
                    </div>
                    <ul style="list-style: none; text-align: left; margin-bottom: 1.5rem;">
                        <li style="padding: 0.5rem 0; display: flex; align-items: center; gap: 0.5rem;">
                            <i class="fas fa-check" style="color: #10b981;"></i>
                            {{ $plan->books_limit }} {{ __('messages.books_access') }}
                        </li>
                        @if($plan->features)
                            @foreach($plan->features as $feature)
                                <li style="padding: 0.5rem 0; display: flex; align-items: center; gap: 0.5rem;">
                                    <i class="fas fa-check" style="color: #10b981;"></i>
                                    {{ $feature }}
                                </li>
                            @endforeach
                        @endif
                    </ul>
                    @auth
                        <a href="#" class="btn {{ $plan->is_popular ? 'btn-primary' : 'btn-outline' }} open-subscribe-modal" data-slug="{{ $plan->slug ?? $plan->id }}" data-price="{{ $plan->price }}" data-name="{{ $plan->localized_name }}" style="width: 100%;">{{ __('messages.subscribe') }}</a>
                    @else
                        <a href="{{ route('register') }}" class="btn {{ $plan->is_popular ? 'btn-primary' : 'btn-outline' }}" style="width: 100%;">{{ __('messages.get_started') }}</a>
                    @endauth
                </div>
            @endforeach
        </div>

        <!-- Author Plans -->
        <h2 class="section-title" style="margin-bottom: 2rem;">{{ __('messages.author_plans') }}</h2>
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 2rem; max-width: 960px; margin-left: auto; margin-right: auto;">
            @foreach($authorPlans as $plan)
                <div class="card card-elevated" style="padding: 2rem; position: relative; {{ $plan->is_popular ? 'border: 2px solid var(--blue-roi);' : '' }}">
                    @if($plan->is_popular)
                        <div style="position: absolute; top: 5px; left: 50%; transform: translateX(-50%); background: var(--gradient-secondary); color: white; padding: 0.25rem 1rem; border-radius: 20px; font-size: 0.8rem; font-weight: 600;">{{ __('messages.popular') }}</div>
                    @endif
                    <h3 style="font-size: 1.3rem; margin-bottom: 0.5rem;">{{ $plan->localized_name }}</h3>
                    <div style="margin-bottom: 1rem;">
                        <span style="font-size: 2.5rem; font-weight: 800; color: var(--orange-fluo);">{{ number_format($plan->price, 0, ',', ' ') }}</span>
                        <span style="color: #64748b;"> XAF/{{ $plan->duration_label }}</span>
                    </div>
                    <ul style="list-style: none; text-align: left; margin-bottom: 1.5rem;">
                        <li style="padding: 0.5rem 0; display: flex; align-items: center; gap: 0.5rem;">
                            <i class="fas fa-check" style="color: #10b981;"></i>
                            {{ $plan->publications_limit }} {{ __('messages.publications') }}
                        </li>
                        @if($plan->features)
                            @foreach($plan->features as $feature)
                                <li style="padding: 0.5rem 0; display: flex; align-items: center; gap: 0.5rem;">
                                    <i class="fas fa-check" style="color: #10b981;"></i>
                                    {{ $feature }}
                                </li>
                            @endforeach
                        @endif
                    </ul>
                    @auth
                        <a href="#" class="btn {{ $plan->is_popular ? 'btn-secondary' : 'btn-outline' }} open-subscribe-modal" data-slug="{{ $plan->slug ?? $plan->id }}" data-price="{{ $plan->price }}" data-name="{{ $plan->localized_name }}" style="width: 100%;">{{ $plan->price == 0 ? 'Commencer' : __('messages.subscribe') }}</a>
                    @else
                        <a href="{{ route('register') }}" class="btn {{ $plan->is_popular ? 'btn-secondary' : 'btn-outline' }}" style="width: 100%;">{{ __('messages.get_started') }}</a>
                    @endauth
                </div>
            @endforeach
        </div>
    </div>
</div>

<x-subscription-modals />
@endsection
