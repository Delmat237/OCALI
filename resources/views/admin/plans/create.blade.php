@extends('layouts.app')
@section('title', __('messages.new_plan'))

@section('content')
<div class="section">
    <div class="container" style="max-width: 600px;">
        <h1 class="section-title" style="margin-bottom: 2rem;">{{ __('messages.new_plan') }}</h1>
        <div class="card card-elevated" style="padding: 2rem;">
            <form action="{{ route('admin.plans.store') }}" method="POST">
                @csrf
                <div style="margin-bottom: 1rem;">
                    <label style="display: block; margin-bottom: 0.5rem; font-weight: 600;">{{ __('messages.name') }} (FR) *</label>
                    <input type="text" name="name" required style="width: 100%; padding: 0.75rem; border: 2px solid #e2e8f0; border-radius: 12px;">
                </div>
                <div style="margin-bottom: 1rem;">
                    <label style="display: block; margin-bottom: 0.5rem; font-weight: 600;">{{ __('messages.name') }} (EN)</label>
                    <input type="text" name="name_en" style="width: 100%; padding: 0.75rem; border: 2px solid #e2e8f0; border-radius: 12px;">
                </div>
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin-bottom: 1rem;">
                    <div>
                        <label style="display: block; margin-bottom: 0.5rem; font-weight: 600;">{{ __('messages.type') }} *</label>
                        <select name="type" required style="width: 100%; padding: 0.75rem; border: 2px solid #e2e8f0; border-radius: 12px;">
                            <option value="reader">Reader</option>
                            <option value="author">Author</option>
                        </select>
                    </div>
                    <div>
                        <label style="display: block; margin-bottom: 0.5rem; font-weight: 600;">{{ __('messages.duration') }} *</label>
                        <select name="duration" required style="width: 100%; padding: 0.75rem; border: 2px solid #e2e8f0; border-radius: 12px;">
                            <option value="monthly">{{ __('messages.monthly') }}</option>
                            <option value="quarterly">{{ __('messages.quarterly') }}</option>
                            <option value="yearly">{{ __('messages.yearly') }}</option>
                        </select>
                    </div>
                </div>
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin-bottom: 1rem;">
                    <div>
                        <label style="display: block; margin-bottom: 0.5rem; font-weight: 600;">{{ __('messages.price') }} (XAF) *</label>
                        <input type="number" name="price" required style="width: 100%; padding: 0.75rem; border: 2px solid #e2e8f0; border-radius: 12px;">
                    </div>
                    <div>
                        <label style="display: block; margin-bottom: 0.5rem; font-weight: 600;">{{ __('messages.duration_days') }} *</label>
                        <input type="number" name="duration_days" required style="width: 100%; padding: 0.75rem; border: 2px solid #e2e8f0; border-radius: 12px;">
                    </div>
                </div>
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin-bottom: 1rem;">
                    <div>
                        <label style="display: block; margin-bottom: 0.5rem; font-weight: 600;">{{ __('messages.books_limit') }}</label>
                        <input type="number" name="books_limit" style="width: 100%; padding: 0.75rem; border: 2px solid #e2e8f0; border-radius: 12px;">
                    </div>
                    <div>
                        <label style="display: block; margin-bottom: 0.5rem; font-weight: 600;">{{ __('messages.commission_rate') }} *</label>
                        <input type="number" name="platform_commission_rate" step="0.01" min="0" max="1" required style="width: 100%; padding: 0.75rem; border: 2px solid #e2e8f0; border-radius: 12px;">
                    </div>
                </div>
                <div style="margin-bottom: 1.5rem; display: flex; gap: 1rem;">
                    <label style="display: flex; align-items: center; gap: 0.5rem;"><input type="checkbox" name="is_popular" value="1"> {{ __('messages.popular') }}</label>
                    <label style="display: flex; align-items: center; gap: 0.5rem;"><input type="checkbox" name="is_active" value="1" checked> {{ __('messages.active') }}</label>
                </div>
                <button type="submit" class="btn btn-primary" style="width: 100%;">{{ __('messages.create_plan') }}</button>
            </form>
        </div>
    </div>
</div>
@endsection
