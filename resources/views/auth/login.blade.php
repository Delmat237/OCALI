@extends('layouts.app')

@section('title', __('messages.login'))

@section('content')
<style>
    .auth-container {
        min-height: calc(100vh - 70px);
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 2rem;
    }

    .auth-card {
        width: 100%;
        max-width: 480px;
        background: white;
        border-radius: 30px;
        padding: 3rem;
        position: relative;
        overflow: hidden;
        box-shadow: 0 25px 80px rgba(0, 0, 0, 0.15);
    }

    .dark .auth-card {
        background: #1e293b;
    }

    /* Geometric accents */
    .auth-card::before {
        content: '';
        position: absolute;
        top: -50px;
        right: -50px;
        width: 150px;
        height: 150px;
        background: var(--gradient-primary);
        border-radius: 50%;
        opacity: 0.1;
    }

    .auth-card::after {
        content: '';
        position: absolute;
        bottom: -30px;
        left: -30px;
        width: 100px;
        height: 100px;
        background: var(--gradient-secondary);
        transform: rotate(45deg);
        opacity: 0.1;
    }

    .auth-header {
        text-align: center;
        margin-bottom: 2rem;
        position: relative;
        z-index: 1;
    }

    .auth-logo {
        width: 80px;
        height: 80px;
        background: white;
        border: 2px solid var(--orange-fluo);
        border-radius: 20px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 1.5rem;
        transform: rotate(-5deg);
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
    }

    .auth-logo img {
        width: 50px;
        height: 50px;
        object-fit: contain;
    }

    .auth-title {
        font-size: 2rem;
        margin-bottom: 0.5rem;
    }

    .auth-subtitle {
        color: #64748b;
    }

    .auth-form {
        position: relative;
        z-index: 1;
    }

    .form-group {
        margin-bottom: 1.5rem;
    }

    .form-label {
        display: block;
        font-weight: 600;
        margin-bottom: 0.5rem;
        color: #1e293b;
    }

    .dark .form-label {
        color: #e2e8f0;
    }

    .form-input {
        width: 100%;
        padding: 1rem 1.25rem;
        border: 2px solid #e2e8f0;
        border-radius: 15px;
        font-size: 1rem;
        transition: all 0.3s ease;
        background: transparent;
    }

    .dark .form-input {
        border-color: #475569;
        color: #f1f5f9;
    }

    .form-input:focus {
        outline: none;
        border-color: var(--orange-fluo);
        box-shadow: 0 0 0 4px rgba(255, 165, 0, 0.1);
    }

    .form-input.is-invalid {
        border-color: #ef4444;
    }

    .form-error {
        color: #ef4444;
        font-size: 0.85rem;
        margin-top: 0.5rem;
    }

    .form-row {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 1.5rem;
    }

    .checkbox-label {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        cursor: pointer;
    }

    .checkbox-label input {
        width: 18px;
        height: 18px;
        accent-color: var(--orange-fluo);
    }

    .forgot-link {
        color: var(--blue-roi);
        text-decoration: none;
        font-weight: 500;
        transition: color 0.3s ease;
    }

    .forgot-link:hover {
        color: var(--orange-fluo);
    }

    .auth-btn {
        width: 100%;
        padding: 1rem;
        font-size: 1.1rem;
        margin-bottom: 1.5rem;
    }

    .auth-divider {
        display: flex;
        align-items: center;
        gap: 1rem;
        margin: 1.5rem 0;
        color: #64748b;
    }

    .auth-divider::before,
    .auth-divider::after {
        content: '';
        flex: 1;
        height: 1px;
        background: #e2e8f0;
    }

    .dark .auth-divider::before,
    .dark .auth-divider::after {
        background: #475569;
    }

    .social-btns {
        display: flex;
        gap: 1rem;
    }

    .social-btn {
        flex: 1;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.75rem;
        padding: 1rem;
        border: 2px solid #e2e8f0;
        border-radius: 15px;
        background: transparent;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
        text-decoration: none;
        color: #475569;
    }

    .dark .social-btn {
        border-color: #475569;
        color: #94a3b8;
    }

    .social-btn:hover {
        border-color: var(--orange-fluo);
        background: rgba(255, 165, 0, 0.05);
    }

    .social-btn i {
        font-size: 1.2rem;
    }

    .social-btn.google i {
        color: #ea4335;
    }

    .social-btn.facebook i {
        color: #1877f2;
    }

    .auth-footer {
        text-align: center;
        margin-top: 2rem;
        color: #64748b;
    }

    .auth-footer a {
        color: var(--orange-fluo);
        text-decoration: none;
        font-weight: 600;
    }

    .auth-footer a:hover {
        text-decoration: underline;
    }

    /* Floating shapes around the form */
    .auth-shapes {
        position: absolute;
        inset: 0;
        pointer-events: none;
        overflow: hidden;
    }

    .auth-shape {
        position: absolute;
        opacity: 0.5;
    }

    .auth-shape-1 {
        top: 10%;
        left: -20px;
        width: 40px;
        height: 40px;
        border: 3px solid var(--orange-fluo);
        border-radius: 50%;
        animation: float 6s ease-in-out infinite;
    }

    .auth-shape-2 {
        bottom: 20%;
        right: -15px;
        width: 30px;
        height: 30px;
        background: var(--blue-roi);
        transform: rotate(45deg);
        animation: float 8s ease-in-out infinite reverse;
    }

    .auth-shape-3 {
        top: 60%;
        left: -10px;
        width: 0;
        height: 0;
        border-left: 15px solid transparent;
        border-right: 15px solid transparent;
        border-bottom: 26px solid var(--orange-fluo);
        animation: rotate 20s linear infinite;
    }
</style>

<div class="auth-container">
    <div class="auth-card animate-slide-up">
        <div class="auth-shapes">
            <div class="auth-shape auth-shape-1"></div>
            <div class="auth-shape auth-shape-2"></div>
            <div class="auth-shape auth-shape-3"></div>
        </div>

        <div class="auth-header">
            <div class="auth-logo">
                <img src="{{ asset('images/logo.png') }}" alt="OCaLi">
            </div>
            <h1 class="auth-title">{{ __('messages.login') }}</h1>
            <p class="auth-subtitle">{{ __('messages.login') }} {{ __('messages.or') }} <a href="{{ route('register') }}">{{ __('messages.register') }}</a></p>
        </div>

        <form method="POST" action="{{ route('login') }}" class="auth-form">
            @csrf

            <div class="form-group">
                <label for="email" class="form-label">{{ __('messages.email') }}</label>
                <input type="email"
                       id="email"
                       name="email"
                       class="form-input @error('email') is-invalid @enderror"
                       value="{{ old('email') }}"
                       required
                       autofocus
                       placeholder="exemple@email.com">
                @error('email')
                    <p class="form-error">{{ $message }}</p>
                @enderror
            </div>

            <div class="form-group">
                <label for="password" class="form-label">{{ __('messages.password') }}</label>
                <div style="position: relative;">
                    <input type="password"
                           id="password"
                           name="password"
                           class="form-input @error('password') is-invalid @enderror"
                           required
                           placeholder="••••••••">
                    <i class="fas fa-eye password-toggle" onclick="togglePassword('password', this)" style="position: absolute; right: 15px; top: 50%; transform: translateY(-50%); cursor: pointer; color: #64748b;"></i>
                </div>
                @error('password')
                    <p class="form-error">{{ $message }}</p>
                @enderror
            </div>

            <script>
                function togglePassword(inputId, icon) {
                    const input = document.getElementById(inputId);
                    if (input.type === 'password') {
                        input.type = 'text';
                        icon.classList.remove('fa-eye');
                        icon.classList.add('fa-eye-slash');
                    } else {
                        input.type = 'password';
                        icon.classList.remove('fa-eye-slash');
                        icon.classList.add('fa-eye');
                    }
                }
            </script>

            <div class="form-row">
                <label class="checkbox-label">
                    <input type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }}>
                    <span>{{ __('messages.remember_me') }}</span>
                </label>
                <a href="{{ route('password.request') }}" class="forgot-link">{{ __('messages.forgot_password') }}</a>
            </div>

            <button type="submit" class="btn btn-primary auth-btn">
                <i class="fas fa-sign-in-alt"></i>
                {{ __('messages.login') }}
            </button>
        </form>

        <div class="auth-divider">{{ __('messages.or_continue_with') }}</div>

        <div class="social-btns">
            <a href="{{ route('auth.google') }}" class="btn btn-outline social-btn">
                <img src="https://www.svgrepo.com/show/475656/google-color.svg" alt="Google" width="20">
                Google
            </a>
            <a href="{{ route('auth.facebook') }}" class="btn btn-outline social-btn">
                <img src="https://www.svgrepo.com/show/475647/facebook-color.svg" alt="Facebook" width="20">
                Facebook
            </a>
        </div>

        <div class="auth-footer">
            {{ __('messages.no_account') }}
            <a href="{{ route('register') }}">{{ __('messages.register') }}</a>
        </div>
    </div>
</div>
@endsection
