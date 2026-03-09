@extends('layouts.app')
@section('title', $user->name)

@section('content')
<div class="section">
    <div class="container" style="max-width: 800px;">
        <a href="{{ route('admin.users.index') }}" class="btn btn-ghost" style="margin-bottom: 1rem;"><i class="fas fa-arrow-left"></i> {{ __('messages.back') }}</a>

        <div class="card card-elevated" style="padding: 2rem; margin-bottom: 1.5rem;">
            <div style="display: flex; gap: 1.5rem; align-items: center; margin-bottom: 1.5rem;">
                <img src="{{ $user->avatar_url }}" alt="" style="width: 80px; height: 80px; border-radius: 50%; object-fit: cover;">
                <div>
                    <h2>{{ $user->name }}</h2>
                    <p style="color: #64748b;">{{ $user->email }}</p>
                    <span style="padding: 0.2rem 0.75rem; border-radius: 15px; font-size: 0.85rem; background: rgba(65,105,225,0.1); color: var(--blue-roi);">{{ $user->role_label }}</span>
                </div>
            </div>

            <form action="{{ route('admin.users.update', $user) }}" method="POST">
                @csrf @method('PUT')
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin-bottom: 1rem;">
                    <div>
                        <label style="display: block; margin-bottom: 0.5rem; font-weight: 600;">{{ __('messages.name') }}</label>
                        <input type="text" name="name" value="{{ $user->name }}" style="width: 100%; padding: 0.75rem; border: 2px solid #e2e8f0; border-radius: 12px;">
                    </div>
                    <div>
                        <label style="display: block; margin-bottom: 0.5rem; font-weight: 600;">{{ __('messages.email') }}</label>
                        <input type="email" name="email" value="{{ $user->email }}" style="width: 100%; padding: 0.75rem; border: 2px solid #e2e8f0; border-radius: 12px;">
                    </div>
                </div>
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin-bottom: 1.5rem;">
                    <div>
                        <label style="display: block; margin-bottom: 0.5rem; font-weight: 600;">{{ __('messages.role') }}</label>
                        <select name="role" style="width: 100%; padding: 0.75rem; border: 2px solid #e2e8f0; border-radius: 12px;">
                            <option value="reader" {{ $user->role === 'reader' ? 'selected' : '' }}>{{ __('messages.role_reader') }}</option>
                            <option value="author" {{ $user->role === 'author' ? 'selected' : '' }}>{{ __('messages.role_author') }}</option>
                            <option value="editor" {{ $user->role === 'editor' ? 'selected' : '' }}>{{ __('messages.role_editor') }}</option>
                            <option value="admin" {{ $user->role === 'admin' ? 'selected' : '' }}>{{ __('messages.role_admin') }}</option>
                        </select>
                    </div>
                    <div>
                        <label style="display: block; margin-bottom: 0.5rem; font-weight: 600;">{{ __('messages.status') }}</label>
                        <select name="is_active" style="width: 100%; padding: 0.75rem; border: 2px solid #e2e8f0; border-radius: 12px;">
                            <option value="1" {{ $user->is_active ? 'selected' : '' }}>{{ __('messages.active') }}</option>
                            <option value="0" {{ !$user->is_active ? 'selected' : '' }}>{{ __('messages.inactive') }}</option>
                        </select>
                    </div>
                </div>
                <button type="submit" class="btn btn-primary">{{ __('messages.save_changes') }}</button>
            </form>
        </div>

        <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 1rem; margin-bottom: 1.5rem;">
            <div class="card card-elevated" style="padding: 1rem; text-align: center;">
                <div style="font-size: 1.5rem; font-weight: 800;">{{ $user->books->count() }}</div>
                <div style="color: #64748b; font-size: 0.85rem;">{{ __('messages.books') }}</div>
            </div>
            <div class="card card-elevated" style="padding: 1rem; text-align: center;">
                <div style="font-size: 1.5rem; font-weight: 800;">{{ $user->subscriptions->count() }}</div>
                <div style="color: #64748b; font-size: 0.85rem;">{{ __('messages.subscriptions') }}</div>
            </div>
            <div class="card card-elevated" style="padding: 1rem; text-align: center;">
                <div style="font-size: 1.5rem; font-weight: 800;">{{ $user->wallet ? number_format($user->wallet->balance, 0, ',', ' ') : 0 }}</div>
                <div style="color: #64748b; font-size: 0.85rem;">{{ __('messages.wallet_balance') }}</div>
            </div>
        </div>
    </div>
</div>
@endsection
