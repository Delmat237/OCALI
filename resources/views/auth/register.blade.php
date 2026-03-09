@extends('layouts.app')

@section('title', __('messages.register'))

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
        max-width: 520px;
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

    .auth-card::before {
        content: '';
        position: absolute;
        top: -50px;
        right: -50px;
        width: 150px;
        height: 150px;
        background: var(--gradient-secondary);
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
        background: var(--gradient-primary);
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
        border: 2px solid var(--blue-roi);
        border-radius: 20px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 1.5rem;
        transform: rotate(5deg);
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
        margin-bottom: 1.25rem;
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
        border-color: var(--blue-roi);
        box-shadow: 0 0 0 4px rgba(65, 105, 225, 0.1);
    }

    .form-input.is-invalid {
        border-color: #ef4444;
    }

    .form-error {
        color: #ef4444;
        font-size: 0.85rem;
        margin-top: 0.5rem;
        display: flex;
        align-items: center;
        gap: 0.35rem;
    }
    .form-success-hint {
        color: #10b981;
        font-size: 0.82rem;
        margin-top: 0.35rem;
        display: flex;
        align-items: center;
        gap: 0.35rem;
    }

    /* Phone group */
    .phone-group {
        display: flex;
        border: 2px solid #e2e8f0;
        border-radius: 15px;
        overflow: hidden;
        transition: border-color 0.3s, box-shadow 0.3s;
        background: transparent;
    }
    .dark .phone-group { border-color: #475569; }
    .phone-group:focus-within {
        border-color: var(--blue-roi);
        box-shadow: 0 0 0 4px rgba(65, 105, 225, 0.1);
    }
    .phone-group.is-invalid { border-color: #ef4444; }
    .phone-group.is-valid   { border-color: #10b981; }

    .country-select {
        display: flex;
        align-items: center;
        gap: 0.4rem;
        padding: 0 0.75rem;
        background: #f8fafc;
        border-right: 2px solid #e2e8f0;
        cursor: pointer;
        white-space: nowrap;
        position: relative;
        min-width: 90px;
    }
    .dark .country-select { background: #1e293b; border-right-color: #475569; }
    .country-select select {
        position: absolute; inset: 0; opacity: 0;
        width: 100%; cursor: pointer;
    }
    .country-flag { font-size: 1.2rem; }
    .country-code { font-weight: 700; font-size: 0.9rem; color: var(--blue-roi); }
    .country-chevron { font-size: 0.6rem; color: #94a3b8; }

    .phone-local-input {
        flex: 1;
        padding: 1rem 1.25rem;
        border: none;
        outline: none;
        font-size: 1rem;
        background: transparent;
        color: inherit;
    }
    .dark .phone-local-input { color: #f1f5f9; }


    /* Role Selection */
    .role-selection {
        margin-bottom: 1.5rem;
    }

    .role-label {
        display: block;
        font-weight: 600;
        margin-bottom: 1rem;
        color: #1e293b;
    }

    .dark .role-label {
        color: #e2e8f0;
    }

    .role-options {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 1rem;
    }

    .role-option {
        position: relative;
    }

    .role-option input {
        position: absolute;
        opacity: 0;
    }

    .role-card {
        display: flex;
        flex-direction: column;
        align-items: center;
        padding: 1.5rem 1rem;
        border: 2px solid #e2e8f0;
        border-radius: 20px;
        cursor: pointer;
        transition: all 0.3s ease;
        text-align: center;
    }

    .dark .role-card {
        border-color: #475569;
    }

    .role-option input:checked + .role-card {
        border-color: var(--blue-roi);
        background: rgba(65, 105, 225, 0.05);
    }

    .role-card:hover {
        border-color: var(--orange-fluo);
    }

    /* ── Password strength meter ───────────────────────────────── */
    .pw-strength-wrap {
        margin-top: 0.6rem;
    }
    .pw-bar-track {
        display: flex; gap: 4px; margin-bottom: 0.45rem;
    }
    .pw-bar-seg {
        flex: 1; height: 5px; border-radius: 99px;
        background: #e2e8f0;
        transition: background 0.35s;
    }
    .pw-bar-seg.s1 { background: #ef4444; }
    .pw-bar-seg.s2 { background: #f97316; }
    .pw-bar-seg.s3 { background: #eab308; }
    .pw-bar-seg.s4 { background: #22c55e; }

    .pw-label {
        font-size: 0.78rem; font-weight: 700;
        margin-bottom: 0.5rem; display: block;
    }
    .strength-0 .pw-label { color: #94a3b8; }
    .strength-1 .pw-label { color: #ef4444; }
    .strength-2 .pw-label { color: #f97316; }
    .strength-3 .pw-label { color: #eab308; }
    .strength-4 .pw-label { color: #22c55e; }

    .pw-reqs {
        display: grid; grid-template-columns: 1fr 1fr;
        gap: 0.3rem 0.75rem;
        list-style: none; padding: 0; margin: 0;
    }
    .pw-req {
        display: flex; align-items: center; gap: 0.35rem;
        font-size: 0.78rem; color: #94a3b8;
        transition: color 0.25s;
    }
    .pw-req .req-icon {
        width: 16px; height: 16px; border-radius: 50%;
        background: #e2e8f0;
        display: flex; align-items: center; justify-content: center;
        font-size: 0.65rem; flex-shrink: 0;
        transition: background 0.25s;
    }
    .pw-req.ok { color: #16a34a; }
    .pw-req.ok .req-icon { background: #dcfce7; color: #16a34a; }
    .pw-req.fail { color: #dc2626; }
    .pw-req.fail .req-icon { background: #fee2e2; color: #dc2626; }

    /* confirm match */
    .confirm-match {
        font-size: 0.8rem; margin-top: 0.4rem;
        display: flex; align-items: center; gap: 0.35rem;
    }
    .confirm-match.ok   { color: #16a34a; }
    .confirm-match.fail { color: #dc2626; }


    .role-icon {
        width: 60px; height: 60px; border-radius: 50%;
        display: flex; align-items: center; justify-content: center;
        font-size: 1.5rem; margin-bottom: 0.75rem;
        transition: all 0.3s ease;
    }

    .role-option:first-child .role-icon {
        background: rgba(255, 165, 0, 0.1);
        color: var(--orange-fluo);
    }

    .role-option:last-child .role-icon {
        background: rgba(65, 105, 225, 0.1);
        color: var(--blue-roi);
    }

    .role-option input:checked + .role-card .role-icon {
        transform: scale(1.1);
    }

    .role-name {
        font-weight: 600;
        color: #1e293b;
        margin-bottom: 0.25rem;
    }

    .dark .role-name {
        color: #f1f5f9;
    }

    .role-desc {
        font-size: 0.8rem;
        color: #64748b;
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
        border-color: var(--blue-roi);
        background: rgba(65, 105, 225, 0.05);
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
        color: var(--blue-roi);
        text-decoration: none;
        font-weight: 600;
    }

    .auth-footer a:hover {
        text-decoration: underline;
    }

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
        top: 15%;
        right: -15px;
        width: 35px;
        height: 35px;
        border: 3px solid var(--blue-roi);
        border-radius: 50%;
        animation: float 7s ease-in-out infinite;
    }

    .auth-shape-2 {
        bottom: 25%;
        left: -10px;
        width: 25px;
        height: 25px;
        background: var(--orange-fluo);
        transform: rotate(45deg);
        animation: float 5s ease-in-out infinite reverse;
    }
</style>

<div class="auth-container">
    <div class="auth-card animate-slide-up">
        <div class="auth-shapes">
            <div class="auth-shape auth-shape-1"></div>
            <div class="auth-shape auth-shape-2"></div>
        </div>

        <div class="auth-header">
            <div class="auth-logo">
                <img src="{{ asset('images/logo.png') }}" alt="OCaLi">
            </div>
            <h1 class="auth-title">{{ __('messages.register') }}</h1>
            <p class="auth-subtitle">Rejoignez notre communauté de lecteurs</p>
        </div>

        <div class="steps-indicator" style="display: flex; justify-content: space-between; margin-bottom: 2rem; position: relative;">
            <div class="step-line" style="position: absolute; top: 50%; left: 0; right: 0; height: 2px; background: #e2e8f0; z-index: 0; transform: translateY(-50%);"></div>
            <div class="step-line-progress" style="position: absolute; top: 50%; left: 0; width: 0%; height: 2px; background: var(--blue-roi); z-index: 0; transform: translateY(-50%); transition: width 0.3s ease;"></div>
            
            <div class="step-item active" style="position: relative; z-index: 1; background: white; width: 30px; height: 30px; border-radius: 50%; display: flex; align-items: center; justify-content: center; border: 2px solid var(--blue-roi); color: var(--blue-roi); font-weight: bold;">1</div>
            <div class="step-item" style="position: relative; z-index: 1; background: white; width: 30px; height: 30px; border-radius: 50%; display: flex; align-items: center; justify-content: center; border: 2px solid #e2e8f0; color: #64748b; font-weight: bold;">2</div>
        </div>

        <form method="POST" action="{{ route('register') }}" class="auth-form" id="registerForm">
            @csrf

            <!-- Step 1: Personal Info -->
            <div class="form-step active" id="step1">
                <div class="form-group">
                    <label for="name" class="form-label">{{ __('messages.name') ?? 'Nom complet' }}</label>
                    <input type="text"
                           id="name"
                           name="name"
                           class="form-input @error('name') is-invalid @enderror"
                           value="{{ old('name') }}"
                           required
                           placeholder="Jean Dupont">
                    @error('name')
                        <p class="form-error">{{ $message }}</p>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="email" class="form-label">{{ __('messages.email') }}</label>
                    <input type="email"
                           id="email"
                           name="email"
                           class="form-input @error('email') is-invalid @enderror"
                           value="{{ old('email') }}"
                           required
                           placeholder="exemple@email.com">
                    @error('email')
                        <p class="form-error">{{ $message }}</p>
                    @enderror
                </div>

                <div class="form-group" id="phoneGroup">
                    <label class="form-label">📱 {{ __('messages.phone') ?? 'Téléphone' }}</label>

                    {{-- Hidden input that holds the full phone (e.g. +237657450314) --}}
                    <input type="hidden" name="phone" id="phoneHidden" value="{{ old('phone') }}">

                    <div class="phone-group @error('phone') is-invalid @enderror" id="phoneGroupEl">
                        {{-- Country code selector --}}
                        <div class="country-select" id="countryDisplay">
                            <span class="country-flag" id="selectedFlag">🇨🇲</span>
                            <span class="country-code" id="selectedCode">+237</span>
                            <span class="country-chevron">▼</span>
                            <select id="countrySelect" onchange="onCountryChange()" title="Sélectionner le pays">
                                <option value="+237" data-flag="🇨🇲" selected>🇨🇲 Cameroun (+237)</option>
                                <option value="+33"  data-flag="🇫🇷">🇫🇷 France (+33)</option>
                                <option value="+32"  data-flag="🇧🇪">🇧🇪 Belgique (+32)</option>
                                <option value="+41"  data-flag="🇨🇭">🇨🇭 Suisse (+41)</option>
                                <option value="+1"   data-flag="🇺🇸">🇺🇸 USA / Canada (+1)</option>
                                <option value="+44"  data-flag="🇬🇧">🇬🇧 Royaume-Uni (+44)</option>
                                <option value="+234" data-flag="🇳🇬">🇳🇬 Nigéria (+234)</option>
                                <option value="+225" data-flag="🇨🇮">🇨🇮 Côte d'Ivoire (+225)</option>
                                <option value="+221" data-flag="🇸🇳">🇸🇳 Sénégal (+221)</option>
                                <option value="+241" data-flag="🇬🇦">🇬🇦 Gabon (+241)</option>
                                <option value="+242" data-flag="🇨🇬">🇨🇬 Congo (+242)</option>
                                <option value="+243" data-flag="🇨🇩">🇨🇩 RD Congo (+243)</option>
                                <option value="+212" data-flag="🇲🇦">🇲🇦 Maroc (+212)</option>
                                <option value="+213" data-flag="🇩🇿">🇩🇿 Algérie (+213)</option>
                                <option value="+216" data-flag="🇹🇳">🇹🇳 Tunisie (+216)</option>
                            </select>
                        </div>
                        {{-- Local number input --}}
                        <input type="tel" id="phoneLocal"
                               class="phone-local-input"
                               placeholder="6 XX XX XX XX"
                               value="{{ old('phone') ? ltrim(old('phone'), '+0123456789') : '' }}"
                               oninput="onPhoneInput()"
                               maxlength="15">
                    </div>

                    {{-- Feedback zone --}}
                    <div id="phoneFeedback">
                        @error('phone')
                            <p class="form-error">⚠️ Numéro invalide — entrez votre numéro local sans indicatif (ex. 657 450 314).</p>
                        @else
                            <p class="form-success-hint" id="phonePreview" style="display:none;"></p>
                        @enderror
                    </div>
                    {{-- Country format hint --}}
                    <p id="phoneFormatHint" style="font-size:.78rem; color:#64748b; margin-top:.3rem; display:flex; align-items:center; gap:.3rem;">
                        <span>📋</span>
                        <span id="phoneFormatText">Format Cameroun : <strong>6 57 45 03 14</strong> — 9 chiffres, commence par <strong>6</strong></span>
                    </p>
                </div>



                <button type="button" class="btn btn-primary auth-btn" onclick="nextStep()">
                    Suivant <i class="fas fa-arrow-right" style="margin-left: 0.5rem;"></i>
                </button>
            </div>

            <!-- Step 2: Role & Security -->
            <div class="form-step" id="step2" style="display: none;">
                <div class="role-selection">
                    <span class="role-label">{{ __('messages.choose_role') }}</span>
                    <div class="role-options">
                        <label class="role-option">
                            <input type="radio" name="role" value="reader" {{ old('role', 'reader') === 'reader' ? 'checked' : '' }}>
                            <div class="role-card">
                                <div class="role-icon">
                                    <i class="fas fa-book-reader"></i>
                                </div>
                                <span class="role-name">{{ __('messages.role_reader') }}</span>
                                <span class="role-desc">{{ __('messages.reader_description') }}</span>
                            </div>
                        </label>
                        <label class="role-option">
                            <input type="radio" name="role" value="author" {{ old('role') === 'author' ? 'checked' : '' }}>
                            <div class="role-card">
                                <div class="role-icon">
                                    <i class="fas fa-pen-fancy"></i>
                                </div>
                                <span class="role-name">{{ __('messages.role_author') }}</span>
                                <span class="role-desc">{{ __('messages.author_description') }}</span>
                            </div>
                        </label>
                    </div>
                </div>

                <div class="form-group">
                    <label for="password" class="form-label">{{ __('messages.password') }}</label>
                    <div style="position: relative;">
                        <input type="password"
                               id="password"
                               name="password"
                               class="form-input @error('password') is-invalid @enderror"
                               required
                               autocomplete="new-password"
                               placeholder="••••••••"
                               oninput="onPasswordInput()">
                        <i class="fas fa-eye password-toggle" onclick="togglePassword('password', this)"
                           style="position: absolute; right: 15px; top: 50%; transform: translateY(-50%); cursor: pointer; color: #64748b;"></i>
                    </div>
                    @error('password')
                        <p class="form-error">{{ $message }}</p>
                    @enderror

                    {{-- Strength meter --}}
                    <div class="pw-strength-wrap strength-0" id="pwStrengthWrap">
                        <div class="pw-bar-track">
                            <div class="pw-bar-seg" id="seg1"></div>
                            <div class="pw-bar-seg" id="seg2"></div>
                            <div class="pw-bar-seg" id="seg3"></div>
                            <div class="pw-bar-seg" id="seg4"></div>
                        </div>
                        <span class="pw-label" id="pwStrengthLabel">Tapez un mot de passe…</span>
                        <ul class="pw-reqs">
                            <li class="pw-req" id="req-length">
                                <span class="req-icon">✕</span>
                                8 caractères min.
                            </li>
                            <li class="pw-req" id="req-upper">
                                <span class="req-icon">✕</span>
                                Majuscule (A–Z)
                            </li>
                            <li class="pw-req" id="req-lower">
                                <span class="req-icon">✕</span>
                                Minuscule (a–z)
                            </li>
                            <li class="pw-req" id="req-digit">
                                <span class="req-icon">✕</span>
                                Chiffre (0–9)
                            </li>
                            <li class="pw-req" id="req-special">
                                <span class="req-icon">✕</span>
                                Caractère spécial (!@#…)
                            </li>
                        </ul>
                    </div>
                </div>


                <div class="form-group">
                    <label for="password_confirmation" class="form-label">{{ __('messages.confirm_password') }}</label>
                    <div style="position: relative;">
                        <input type="password"
                               id="password_confirmation"
                               name="password_confirmation"
                               class="form-input"
                               required
                               autocomplete="new-password"
                               placeholder="••••••••"
                               oninput="onConfirmInput()">
                        <i class="fas fa-eye password-toggle" onclick="togglePassword('password_confirmation', this)"
                           style="position: absolute; right: 15px; top: 50%; transform: translateY(-50%); cursor: pointer; color: #64748b;"></i>
                    </div>
                    <div class="confirm-match" id="confirmFeedback" style="display:none;"></div>
                </div>


                <div style="display: flex; gap: 1rem;">
                    <button type="button" class="btn btn-outline auth-btn" onclick="prevStep()" style="background: transparent; border: 2px solid #e2e8f0; color: #64748b;">
                        {{ __('messages.back') }}
                    </button>
                    <button type="submit" class="btn btn-secondary auth-btn">
                        <i class="fas fa-user-plus"></i>
                        {{ __('messages.register') }}
                    </button>
                </div>
            </div>
        </form>

        <script>
            // ── Password strength ───────────────────────────────────────────
            const pwLabels = [
                'Tapez un mot de passe\u2026',
                '\ud83d\udd34 Tr\u00e8s faible',
                '\ud83d\udfe0 Faible',
                '\ud83d\udfe1 Moyen',
                '\ud83d\udfe2 Fort \u2014 Bon mot de passe !'
            ];

            function setReq(id, ok) {
                const el   = document.getElementById(id);
                const icon = el.querySelector('.req-icon');
                el.classList.toggle('ok',   ok);
                el.classList.toggle('fail', !ok && document.getElementById('password').value.length > 0);
                icon.textContent = ok ? '\u2713' : '\u2715';
            }

            function onPasswordInput() {
                const pw = document.getElementById('password').value;

                const checks = {
                    length:  pw.length >= 8,
                    upper:   /[A-Z]/.test(pw),
                    lower:   /[a-z]/.test(pw),
                    digit:   /\d/.test(pw),
                    special: /[^A-Za-z0-9]/.test(pw),
                };

                setReq('req-length',  checks.length);
                setReq('req-upper',   checks.upper);
                setReq('req-lower',   checks.lower);
                setReq('req-digit',   checks.digit);
                setReq('req-special', checks.special);

                const score = Object.values(checks).filter(Boolean).length; // 0\u20135

                // Map 5 criteria \u2192 4 bar levels
                const level = pw.length === 0 ? 0
                    : score <= 1 ? 1
                    : score <= 2 ? 2
                    : score <= 3 ? 3
                    : 4;

                const wrap = document.getElementById('pwStrengthWrap');
                wrap.className = 'pw-strength-wrap strength-' + level;
                document.getElementById('pwStrengthLabel').textContent = pwLabels[level];

                const segClass = ['', 's1', 's2', 's3', 's4'];
                for (let i = 1; i <= 4; i++) {
                    const seg = document.getElementById('seg' + i);
                    seg.className = 'pw-bar-seg' + (i <= level ? ' ' + segClass[level] : '');
                }

                // Update confirm field if already typed
                onConfirmInput();
            }

            function onConfirmInput() {
                const pw   = document.getElementById('password').value;
                const conf = document.getElementById('password_confirmation').value;
                const fb   = document.getElementById('confirmFeedback');

                if (!conf) { fb.style.display = 'none'; return; }

                fb.style.display = 'flex';
                if (pw === conf) {
                    fb.className   = 'confirm-match ok';
                    fb.textContent = '\u2705 Les mots de passe correspondent.';
                } else {
                    fb.className   = 'confirm-match fail';
                    fb.textContent = '\u26a0\ufe0f Les mots de passe ne correspondent pas.';
                }
            }

            // ── Country code selector ───────────────────────────────────────
            // ── Country rules table ─────────────────────────────────────────
            const countryRules = {
                '+237': { digits: 9, prefix: /^[62]/,    example: '6 57 45 03 14', hint: '9 chiffres, commence par <strong>6</strong> (mobile) ou <strong>2</strong> (fixe)' },
                '+33' : { digits: 9, prefix: /^[67]/,    example: '6 12 34 56 78', hint: '9 chiffres (sans le 0 initial)' },
                '+32' : { digits: 9, prefix: /^[4]/,     example: '4 70 12 34 56', hint: '9 chiffres (sans le 0 initial)' },
                '+41' : { digits: 9, prefix: /^[7]/,     example: '7 12 34 56 78', hint: '9 chiffres (sans le 0 initial)' },
                '+1'  : { digits: 10, prefix: /^[2-9]/, example: '800 555 0199',  hint: '10 chiffres (indicatif régional inclus)' },
                '+44' : { digits: 10, prefix: /^[7]/,    example: '7700 900000',   hint: '10 chiffres (sans le 0 initial)' },
                '+234': { digits: 10, prefix: /^[7-9]/,  example: '803 123 4567',  hint: '10 chiffres, commence par 7, 8 ou 9' },
                '+225': { digits: 10, prefix: /^[0]/,    example: '07 12 34 56 78',hint: '10 chiffres (avec 0 initial)' },
                '+221': { digits: 9,  prefix: /^[7]/,    example: '77 123 45 67',  hint: '9 chiffres' },
                '+241': { digits: 7,  prefix: null,      example: '07 12 34 5',    hint: '7 chiffres' },
                '+242': { digits: 9,  prefix: null,      example: '06 123 4567',   hint: '9 chiffres' },
                '+243': { digits: 9,  prefix: /^[8]/,    example: '81 234 5678',   hint: '9 chiffres' },
                '+212': { digits: 9,  prefix: /^[6]/,    example: '6 12 34 56 78', hint: '9 chiffres (sans le 0 initial)' },
                '+213': { digits: 9,  prefix: /^[5-7]/,  example: '5 51 23 45 67', hint: '9 chiffres (sans le 0 initial)' },
                '+216': { digits: 8,  prefix: /^[2-5]/,  example: '20 123 456',    hint: '8 chiffres' },
            };

            function onCountryChange() {
                const sel = document.getElementById('countrySelect');
                const opt = sel.options[sel.selectedIndex];
                const code = opt.value;
                document.getElementById('selectedCode').textContent = code;
                document.getElementById('selectedFlag').textContent = opt.dataset.flag;

                // Update placeholder and hint
                const rule = countryRules[code];
                if (rule) {
                    document.getElementById('phoneLocal').placeholder = rule.example;
                    document.getElementById('phoneFormatText').innerHTML =
                        'Format ' + opt.text.split(' ')[1] + ' : <strong>' + rule.example + '</strong> — ' + rule.hint;
                }
                onPhoneInput();
            }

            function onPhoneInput() {
                const code  = document.getElementById('countrySelect').value;
                const local = document.getElementById('phoneLocal').value.replace(/\D/g, '');
                const full  = local ? code + local : '';
                document.getElementById('phoneHidden').value = full;

                const group   = document.getElementById('phoneGroupEl');
                const preview = document.getElementById('phonePreview');

                if (local.length === 0) {
                    group.classList.remove('is-valid', 'is-invalid');
                    preview.style.display = 'none';
                    return;
                }

                const rule  = countryRules[code];
                const rightLength = rule ? local.length === rule.digits : local.length >= 7 && local.length <= 12;
                const rightPrefix = rule && rule.prefix ? rule.prefix.test(local) : true;
                const valid = rightLength && rightPrefix;

                group.classList.toggle('is-valid',   valid);
                group.classList.toggle('is-invalid', !valid);

                preview.style.display = 'flex';
                if (valid) {
                    preview.style.color = '#16a34a';
                    preview.textContent = '✅ Numéro complet : ' + full;
                } else if (!rightLength) {
                    const exp = rule ? rule.digits : '7-12';
                    preview.style.color = '#ef4444';
                    preview.textContent = '⚠️ ' + local.length + ' chiffre(s) saisi(s), ' + exp + ' attendu(s).';
                } else {
                    preview.style.color = '#ef4444';
                    preview.textContent = '⚠️ Ce numéro ne correspond pas au format attendu (ex. ' + (rule ? rule.example : '') + ').';
                }
            }


            // ── Step navigation ─────────────────────────────────────────────
            function nextStep() {
                const step1 = document.getElementById('step1');

                // Validate phone before advancing
                const full = document.getElementById('phoneHidden').value;
                if (!full || !/^\+[1-9]\d{8,14}$/.test(full)) {
                    document.getElementById('phoneGroupEl').classList.add('is-invalid');
                    const preview = document.getElementById('phonePreview');
                    preview.style.display = 'flex';
                    preview.style.color   = '#ef4444';
                    preview.textContent   = '⚠️ Veuillez entrer un numéro de téléphone valide avec indicatif.';
                    document.getElementById('phoneLocal').focus();
                    return;
                }

                const inputs = step1.querySelectorAll('input[required]');
                let valid = true;
                inputs.forEach(input => {
                    if (input.id === 'phoneLocal') return; // handled above
                    if (!input.checkValidity()) {
                        input.reportValidity();
                        valid = false;
                    }
                });

                if (valid) {
                    document.getElementById('step1').style.display = 'none';
                    document.getElementById('step2').style.display = 'block';
                    updateProgress(2);
                }
            }

            function prevStep() {
                document.getElementById('step2').style.display = 'none';
                document.getElementById('step1').style.display = 'block';
                updateProgress(1);
            }

            function updateProgress(step) {
                const progress = document.querySelector('.step-line-progress');
                const items    = document.querySelectorAll('.step-item');
                if (step === 2) {
                    progress.style.width    = '100%';
                    items[1].style.borderColor = 'var(--blue-roi)';
                    items[1].style.color       = 'var(--blue-roi)';
                } else {
                    progress.style.width    = '0%';
                    items[1].style.borderColor = '#e2e8f0';
                    items[1].style.color       = '#64748b';
                }
            }

            function togglePassword(inputId, icon) {
                const input = document.getElementById(inputId);
                if (input.type === 'password') {
                    input.type = 'text';
                    icon.classList.replace('fa-eye', 'fa-eye-slash');
                } else {
                    input.type = 'password';
                    icon.classList.replace('fa-eye-slash', 'fa-eye');
                }
            }

            // Pre-fill on server-side error (old phone value)
            (function restorePhone() {
                const old = '{{ old("phone") }}';
                if (!old) return;
                const match = old.match(/^(\+\d{1,4})(\d+)$/);
                if (match) {
                    const sel = document.getElementById('countrySelect');
                    for (let i = 0; i < sel.options.length; i++) {
                        if (sel.options[i].value === match[1]) {
                            sel.selectedIndex = i;
                            onCountryChange();
                            break;
                        }
                    }
                    document.getElementById('phoneLocal').value = match[2];
                } else {
                    document.getElementById('phoneLocal').value = old.replace(/^\+\d{1,4}/, '');
                }
                onPhoneInput();
            })();
        </script>


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
            {{ __('messages.already_account') }}
            <a href="{{ route('login') }}">{{ __('messages.login') }}</a>
        </div>
    </div>
</div>
@endsection
