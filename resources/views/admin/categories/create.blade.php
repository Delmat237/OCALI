@extends('layouts.app')
@section('title', __('messages.new_category'))

@section('content')
<div class="section">
    <div class="container" style="max-width: 600px;">
        <h1 class="section-title" style="margin-bottom: 2rem;">{{ __('messages.new_category') }}</h1>
        <div class="card card-elevated" style="padding: 2rem;">
            <form action="{{ route('admin.categories.store') }}" method="POST">
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
                        <label style="display: block; margin-bottom: 0.5rem; font-weight: 600;">{{ __('messages.icon') }} (FA class)</label>
                        <input type="text" name="icon" placeholder="fa-book" style="width: 100%; padding: 0.75rem; border: 2px solid #e2e8f0; border-radius: 12px;">
                    </div>
                    <div>
                        <label style="display: block; margin-bottom: 0.5rem; font-weight: 600;">{{ __('messages.color') }}</label>
                        <input type="color" name="color" value="#4169E1" style="width: 100%; height: 45px; border: 2px solid #e2e8f0; border-radius: 12px;">
                    </div>
                </div>
                <div style="margin-bottom: 1.5rem;">
                    <label style="display: flex; align-items: center; gap: 0.5rem;"><input type="checkbox" name="is_active" value="1" checked> {{ __('messages.active') }}</label>
                </div>
                <button type="submit" class="btn btn-primary" style="width: 100%;">{{ __('messages.create') }}</button>
            </form>
        </div>
    </div>
</div>
@endsection
