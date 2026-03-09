@extends('layouts.app')
@section('title', __('messages.initial_setup'))

@push('styles')
<style>
    .setup-container {
        max-width: 550px;
        margin: 0 auto;
        padding: 2rem;
    }
    .setup-card {
        background: white;
        border-radius: 20px;
        padding: 2.5rem;
        box-shadow: 0 10px 40px rgba(0,0,0,0.1);
    }
    .dark .setup-card {
        background: #1e293b;
    }
    .setup-header {
        text-align: center;
        margin-bottom: 2rem;
    }
    .setup-header h1 {
        font-size: 1.6rem;
        margin-bottom: 0.5rem;
    }
    .setup-header p {
        color: #64748b;
        font-size: 0.95rem;
    }
    .setup-badge {
        display: inline-block;
        background: linear-gradient(135deg, #FFA500, #FF6B00);
        color: white;
        padding: 0.35rem 1rem;
        border-radius: 20px;
        font-size: 0.8rem;
        font-weight: 600;
        margin-bottom: 1rem;
    }
    .form-group {
        margin-bottom: 1.25rem;
    }
    .form-group label {
        display: block;
        font-weight: 600;
        font-size: 0.9rem;
        margin-bottom: 0.4rem;
        color: #1e293b;
    }
    .dark .form-group label {
        color: #e2e8f0;
    }
    .form-control {
        width: 100%;
        padding: 0.75rem 1rem;
        border: 2px solid #e2e8f0;
        border-radius: 12px;
        font-size: 0.95rem;
        transition: border-color 0.3s ease;
        background: transparent;
        color: inherit;
    }
    .dark .form-control {
        border-color: #334155;
    }
    .form-control:focus {
        outline: none;
        border-color: var(--blue-roi);
    }
    .role-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 0.75rem;
    }
    .role-option {
        position: relative;
    }
    .role-option input[type="radio"] {
        position: absolute;
        opacity: 0;
        pointer-events: none;
    }
    .role-option label {
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 0.4rem;
        padding: 1rem 0.75rem;
        border: 2px solid #e2e8f0;
        border-radius: 12px;
        cursor: pointer;
        transition: all 0.3s ease;
        text-align: center;
    }
    .dark .role-option label {
        border-color: #334155;
    }
    .role-option input:checked + label {
        border-color: var(--blue-roi);
        background: rgba(65, 105, 225, 0.08);
    }
    .role-option label:hover {
        border-color: var(--orange-fluo);
    }
    .role-option i {
        font-size: 1.5rem;
        color: var(--blue-roi);
    }
    .role-option .role-name {
        font-weight: 600;
        font-size: 0.9rem;
    }
    .role-option .role-desc {
        font-size: 0.75rem;
        color: #64748b;
    }
    .form-error {
        color: #ef4444;
        font-size: 0.85rem;
        margin-top: 0.25rem;
    }
</style>
@endpush

@section('content')
<div class="section">
    <div class="setup-container">
        <div class="setup-card animate-slide-up">
            <div class="setup-header">
                <span class="setup-badge"><i class="fas fa-cog"></i> {{ __('messages.first_time_setup') }}</span>
                <h1>{{ __('messages.create_first_user') }}</h1>
                <p>{{ __('messages.setup_description') }}</p>
            </div>

            <form action="{{ route('setup.store') }}" method="POST">
                @csrf

                <div class="form-group">
                    <label for="name">{{ __('messages.name') }}</label>
                    <input type="text" id="name" name="name" class="form-control" value="{{ old('name') }}" required autofocus>
                    @error('name') <p class="form-error">{{ $message }}</p> @enderror
                </div>

                <div class="form-group">
                    <label for="email">{{ __('messages.email') }}</label>
                    <input type="email" id="email" name="email" class="form-control" value="{{ old('email') }}" required>
                    @error('email') <p class="form-error">{{ $message }}</p> @enderror
                </div>

                <div class="form-group">
                    <label for="password">{{ __('messages.password') }}</label>
                    <input type="password" id="password" name="password" class="form-control" required>
                    @error('password') <p class="form-error">{{ $message }}</p> @enderror
                </div>

                <div class="form-group">
                    <label for="password_confirmation">{{ __('messages.confirm_password') }}</label>
                    <input type="password" id="password_confirmation" name="password_confirmation" class="form-control" required>
                </div>

                <div class="form-group">
                    <label>{{ __('messages.choose_role') }}</label>
                    <div class="role-grid">
                        <div class="role-option">
                            <input type="radio" id="role_admin" name="role" value="admin" {{ old('role', 'admin') === 'admin' ? 'checked' : '' }}>
                            <label for="role_admin">
                                <i class="fas fa-shield-halved"></i>
                                <span class="role-name">{{ __('messages.role_admin') }}</span>
                                <span class="role-desc">{{ __('messages.admin_description') }}</span>
                            </label>
                        </div>
                        <div class="role-option">
                            <input type="radio" id="role_author" name="role" value="author" {{ old('role') === 'author' ? 'checked' : '' }}>
                            <label for="role_author">
                                <i class="fas fa-pen-nib"></i>
                                <span class="role-name">{{ __('messages.role_author') }}</span>
                                <span class="role-desc">{{ __('messages.author_description') }}</span>
                            </label>
                        </div>
                        <div class="role-option">
                            <input type="radio" id="role_reader" name="role" value="reader" {{ old('role') === 'reader' ? 'checked' : '' }}>
                            <label for="role_reader">
                                <i class="fas fa-book-reader"></i>
                                <span class="role-name">{{ __('messages.role_reader') }}</span>
                                <span class="role-desc">{{ __('messages.reader_description') }}</span>
                            </label>
                        </div>
                        <div class="role-option">
                            <input type="radio" id="role_editor" name="role" value="editor" {{ old('role') === 'editor' ? 'checked' : '' }}>
                            <label for="role_editor">
                                <i class="fas fa-newspaper"></i>
                                <span class="role-name">{{ __('messages.role_editor') }}</span>
                                <span class="role-desc">{{ __('messages.editor_description') }}</span>
                            </label>
                        </div>
                    </div>
                    @error('role') <p class="form-error">{{ $message }}</p> @enderror
                </div>

                <button type="submit" class="btn btn-primary" style="width: 100%; margin-top: 0.5rem;">
                    <i class="fas fa-user-plus"></i> {{ __('messages.create_account') }}
                </button>
            </form>
        </div>
    </div>
</div>
@endsection
