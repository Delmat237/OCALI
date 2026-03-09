@extends('layouts.app')
@section('title', __('messages.edit_plan'))

@section('content')
<div class="section">
    <div class="container" style="max-width: 600px;">
        <h1 class="section-title" style="margin-bottom: 2rem;">{{ __('messages.edit_plan') }}</h1>
        <div class="card card-elevated" style="padding: 2rem;">
            <form action="{{ route('admin.plans.update', $plan) }}" method="POST">
                @csrf @method('PUT')
                <div style="margin-bottom: 1rem;">
                    <label style="display: block; margin-bottom: 0.5rem; font-weight: 600;">{{ __('messages.name') }} (FR)</label>
                    <input type="text" name="name" value="{{ $plan->name }}" required style="width: 100%; padding: 0.75rem; border: 2px solid #e2e8f0; border-radius: 12px;">
                </div>
                <div style="margin-bottom: 1rem;">
                    <label style="display: block; margin-bottom: 0.5rem; font-weight: 600;">{{ __('messages.name') }} (EN)</label>
                    <input type="text" name="name_en" value="{{ $plan->name_en }}" style="width: 100%; padding: 0.75rem; border: 2px solid #e2e8f0; border-radius: 12px;">
                </div>
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin-bottom: 1rem;">
                    <div>
                        <label style="display: block; margin-bottom: 0.5rem; font-weight: 600;">{{ __('messages.price') }} (XAF)</label>
                        <input type="number" name="price" value="{{ $plan->price }}" required style="width: 100%; padding: 0.75rem; border: 2px solid #e2e8f0; border-radius: 12px;">
                    </div>
                    <div>
                        <label style="display: block; margin-bottom: 0.5rem; font-weight: 600;">{{ __('messages.commission_rate') }}</label>
                        <input type="number" name="platform_commission_rate" value="{{ $plan->platform_commission_rate }}" step="0.01" min="0" max="1" required style="width: 100%; padding: 0.75rem; border: 2px solid #e2e8f0; border-radius: 12px;">
                    </div>
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin-bottom: 1rem;">
                    <div>
                        <label style="display: block; margin-bottom: 0.5rem; font-weight: 600;">{{ __('messages.books_limit') }}</label>
                        <input type="number" name="books_limit" value="{{ $plan->books_limit }}" min="0" style="width: 100%; padding: 0.75rem; border: 2px solid #e2e8f0; border-radius: 12px;">
                    </div>
                    <div>
                        <label style="display: block; margin-bottom: 0.5rem; font-weight: 600;">{{ __('messages.publications_limit') }}</label>
                        <input type="number" name="publications_limit" value="{{ $plan->publications_limit }}" min="0" style="width: 100%; padding: 0.75rem; border: 2px solid #e2e8f0; border-radius: 12px;">
                    </div>
                </div>

                <div style="margin-bottom: 1rem;">
                    <label style="display: block; margin-bottom: 0.5rem; font-weight: 600;">{{ __('messages.description') }}</label>
                    <textarea name="description" rows="3" style="width: 100%; padding: 0.75rem; border: 2px solid #e2e8f0; border-radius: 12px;">{{ $plan->description }}</textarea>
                </div>

                <div style="margin-bottom: 1rem;">
                    <label style="display: block; margin-bottom: 0.5rem; font-weight: 600;">{{ __('messages.features') }} ({{ __('messages.one_per_line') }})</label>
                    <textarea name="features" rows="5" style="width: 100%; padding: 0.75rem; border: 2px solid #e2e8f0; border-radius: 12px;">{{ is_array($plan->features) ? implode("\n", $plan->features) : $plan->features }}</textarea>
                </div>
                <div style="margin-bottom: 1.5rem; display: flex; gap: 1rem;">
                    <label style="display: flex; align-items: center; gap: 0.5rem;"><input type="checkbox" name="is_popular" value="1" {{ $plan->is_popular ? 'checked' : '' }}> {{ __('messages.popular') }}</label>
                    <label style="display: flex; align-items: center; gap: 0.5rem;"><input type="checkbox" name="is_active" value="1" {{ $plan->is_active ? 'checked' : '' }}> {{ __('messages.active') }}</label>
                </div>
                <button type="submit" class="btn btn-primary" style="width: 100%;">{{ __('messages.save_changes') }}</button>
            </form>
        </div>
    </div>
</div>
@endsection
