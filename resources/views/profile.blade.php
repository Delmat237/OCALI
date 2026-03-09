@extends('layouts.app')
@section('title', __('messages.profile'))

@section('content')
<div class="section">
    <div class="container" style="max-width: 700px;">
        <h1 class="section-title" style="margin-bottom: 2rem;">{{ __('messages.profile') }}</h1>

        <!-- Profile Info -->
        <div class="card card-elevated" style="padding: 2rem; margin-bottom: 1.5rem;">
            <h3 style="margin-bottom: 1rem;">{{ __('messages.personal_info') }}</h3>
            <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div style="display: flex; align-items: center; gap: 1.5rem; margin-bottom: 1.5rem;">
                    <img src="{{ $user->avatar_url }}" alt="" style="width: 80px; height: 80px; border-radius: 50%; object-fit: cover;">
                    <div>
                        <input type="file" name="avatar" id="avatar" style="display: none;">
                        <label for="avatar" class="btn btn-outline" style="cursor: pointer; font-size: 0.85rem;">{{ __('messages.change_avatar') }}</label>
                    </div>
                </div>
                <div style="margin-bottom: 1rem;">
                    <label style="display: block; margin-bottom: 0.5rem; font-weight: 600;">{{ __('messages.name') }}</label>
                    <input type="text" name="name" value="{{ old('name', $user->name) }}" required
                        style="width: 100%; padding: 0.75rem; border: 2px solid #e2e8f0; border-radius: 12px;">
                    @error('name') <small style="color: #ef4444;">{{ $message }}</small> @enderror
                </div>
                <div style="margin-bottom: 1rem;">
                    <label style="display: block; margin-bottom: 0.5rem; font-weight: 600;">{{ __('messages.email') }}</label>
                    <input type="email" name="email" value="{{ old('email', $user->email) }}" required
                        style="width: 100%; padding: 0.75rem; border: 2px solid #e2e8f0; border-radius: 12px;">
                    @error('email') <small style="color: #ef4444;">{{ $message }}</small> @enderror
                </div>
                <div style="margin-bottom: 1rem;">
                    <label style="display: block; margin-bottom: 0.5rem; font-weight: 600;">{{ __('messages.phone') }}</label>
                    <input type="text" name="phone" value="{{ old('phone', $user->phone) }}"
                        style="width: 100%; padding: 0.75rem; border: 2px solid #e2e8f0; border-radius: 12px;">
                </div>
                <div style="margin-bottom: 1.5rem;">
                    <label style="display: block; margin-bottom: 0.5rem; font-weight: 600;">{{ __('messages.bio') }}</label>
                    <textarea name="bio" rows="3" style="width: 100%; padding: 0.75rem; border: 2px solid #e2e8f0; border-radius: 12px; resize: vertical;">{{ old('bio', $user->bio) }}</textarea>
                </div>
                <button type="submit" class="btn btn-primary">{{ __('messages.save_changes') }}</button>
            </form>
        </div>

        <!-- Change Password -->
        <div class="card card-elevated" style="padding: 2rem; margin-bottom: 1.5rem;">
            <h3 style="margin-bottom: 1rem;">{{ __('messages.change_password') }}</h3>
            <form action="{{ route('profile.password') }}" method="POST">
                @csrf
                @method('PUT')
                <div style="margin-bottom: 1rem;">
                    <label style="display: block; margin-bottom: 0.5rem; font-weight: 600;">{{ __('messages.current_password') }}</label>
                    <input type="password" name="current_password" required
                        style="width: 100%; padding: 0.75rem; border: 2px solid #e2e8f0; border-radius: 12px;">
                </div>
                <div style="margin-bottom: 1rem;">
                    <label style="display: block; margin-bottom: 0.5rem; font-weight: 600;">{{ __('messages.new_password') }}</label>
                    <input type="password" name="password" required
                        style="width: 100%; padding: 0.75rem; border: 2px solid #e2e8f0; border-radius: 12px;">
                </div>
                <div style="margin-bottom: 1.5rem;">
                    <label style="display: block; margin-bottom: 0.5rem; font-weight: 600;">{{ __('messages.confirm_password') }}</label>
                    <input type="password" name="password_confirmation" required
                        style="width: 100%; padding: 0.75rem; border: 2px solid #e2e8f0; border-radius: 12px;">
                </div>
                <button type="submit" class="btn btn-secondary">{{ __('messages.update_password') }}</button>
            </form>
        </div>

        <!-- Delete Account -->
        <div class="card" style="padding: 2rem; border: 2px solid #fecaca;">
            <h3 style="color: #ef4444; margin-bottom: 0.75rem;">{{ __('messages.delete_account') }}</h3>
            <p style="color: #64748b; margin-bottom: 1rem; font-size: 0.9rem;">{{ __('messages.delete_account_warning') }}</p>
            <form action="{{ route('profile.delete') }}" method="POST" onsubmit="return confirm('{{ __('messages.confirm_delete_account') }}')">
                @csrf
                @method('DELETE')
                <input type="password" name="password" placeholder="{{ __('messages.enter_password') }}" required
                    style="padding: 0.75rem; border: 2px solid #e2e8f0; border-radius: 12px; margin-right: 0.5rem;">
                <button type="submit" style="background: #ef4444; color: white; padding: 0.75rem 1.5rem; border-radius: 50px; border: none; cursor: pointer; font-weight: 600;">{{ __('messages.delete_my_account') }}</button>
            </form>
        </div>
    </div>
</div>
@endsection
