@extends('layouts.app')
@section('title', __('messages.settings'))

@section('content')
<div class="section">
    <div class="container" style="max-width: 700px;">
        <h1 class="section-title" style="margin-bottom: 2rem;">{{ __('messages.settings') }}</h1>

        <form action="{{ route('admin.settings.update') }}" method="POST">
            @csrf @method('PUT')

            @foreach($settings as $group => $groupSettings)
                <div class="card card-elevated" style="padding: 1.5rem; margin-bottom: 1.5rem;">
                    <h3 style="margin-bottom: 1rem; text-transform: capitalize;">{{ $group }}</h3>
                    @foreach($groupSettings as $setting)
                        <div style="margin-bottom: 1rem;">
                            <label style="display: block; margin-bottom: 0.5rem; font-weight: 600;">{{ str_replace('_', ' ', ucfirst($setting->key)) }}</label>
                            @if($setting->type === 'boolean')
                                <select name="{{ $setting->key }}" style="width: 100%; padding: 0.75rem; border: 2px solid #e2e8f0; border-radius: 12px;">
                                    <option value="1" {{ $setting->value ? 'selected' : '' }}>{{ __('messages.yes') }}</option>
                                    <option value="0" {{ !$setting->value ? 'selected' : '' }}>{{ __('messages.no') }}</option>
                                </select>
                            @else
                                <input type="{{ $setting->type === 'integer' || $setting->type === 'float' ? 'number' : 'text' }}"
                                    name="{{ $setting->key }}" value="{{ $setting->value }}"
                                    step="{{ $setting->type === 'float' ? '0.01' : '1' }}"
                                    style="width: 100%; padding: 0.75rem; border: 2px solid #e2e8f0; border-radius: 12px;">
                            @endif
                            @if($setting->description)
                                <small style="color: #94a3b8;">{{ $setting->description }}</small>
                            @endif
                        </div>
                    @endforeach
                </div>
            @endforeach

            <button type="submit" class="btn btn-primary" style="width: 100%;">{{ __('messages.save_settings') }}</button>
        </form>
    </div>
</div>
@endsection
