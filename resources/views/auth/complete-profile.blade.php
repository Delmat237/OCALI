@extends('layouts.app')
@section('title', 'Compléter votre profil')

@section('content')
<style>
    .auth-container {
        min-height: calc(100vh - 70px);
        display: flex; align-items: center; justify-content: center; padding: 2rem;
    }
    .auth-card {
        width: 100%; max-width: 500px;
        background: white; border-radius: 30px; padding: 3rem; position: relative; overflow: hidden;
        box-shadow: 0 25px 80px rgba(0,0,0,.15);
    }
    .dark .auth-card { background: #1e293b; }

    .social-avatar {
        width: 80px; height: 80px; border-radius: 50%;
        object-fit: cover; border: 3px solid var(--blue-roi);
        margin: 0 auto 1rem; display: block;
    }
    .social-avatar-placeholder {
        width: 80px; height: 80px; border-radius: 50%;
        background: linear-gradient(135deg, var(--blue-roi), var(--orange-fluo));
        display: flex; align-items: center; justify-content: center;
        font-size: 2rem; color: white; margin: 0 auto 1rem;
    }

    .auth-title { font-size: 1.75rem; text-align: center; margin-bottom: .25rem; }
    .auth-subtitle { text-align: center; color: #64748b; font-size: .93rem; margin-bottom: 2rem; }

    .form-group { margin-bottom: 1.25rem; }
    .form-label { display: block; font-weight: 600; margin-bottom: .5rem; color: #1e293b; }
    .dark .form-label { color: #e2e8f0; }
    .form-error {
        color: #ef4444; font-size: .85rem; margin-top: .5rem;
        display: flex; align-items: center; gap: .35rem;
    }

    /* Role cards */
    .role-options { display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; }
    .role-option { position: relative; }
    .role-option input { position: absolute; opacity: 0; }
    .role-card {
        display: flex; flex-direction: column; align-items: center;
        padding: 1.5rem 1rem; border: 2px solid #e2e8f0; border-radius: 20px;
        cursor: pointer; transition: all .3s;
    }
    .dark .role-card { border-color: #475569; }
    .role-option input:checked + .role-card { border-color: var(--blue-roi); background: rgba(65,105,225,.05); }
    .role-card:hover { border-color: var(--orange-fluo); }
    .role-icon-complete {
        width: 60px; height: 60px; border-radius: 50%;
        display: flex; align-items: center; justify-content: center; font-size: 1.5rem; margin-bottom: .75rem;
    }
    .role-option:first-child .role-icon-complete { background: rgba(255,165,0,.1); color: var(--orange-fluo); }
    .role-option:last-child  .role-icon-complete { background: rgba(65,105,225,.1); color: var(--blue-roi); }
    .role-name { font-weight: 600; color: #1e293b; }
    .dark .role-name { color: #f1f5f9; }
    .role-desc-small { font-size: .78rem; color: #64748b; text-align: center; margin-top: .25rem; }

    /* Phone group */
    .phone-group-cp {
        display: flex; border: 2px solid #e2e8f0; border-radius: 15px; overflow: hidden;
        transition: border-color .3s, box-shadow .3s;
    }
    .dark .phone-group-cp { border-color: #475569; }
    .phone-group-cp:focus-within { border-color: var(--blue-roi); box-shadow: 0 0 0 4px rgba(65,105,225,.1); }
    .phone-group-cp.is-invalid { border-color: #ef4444; }
    .phone-group-cp.is-valid   { border-color: #10b981; }
    .country-select-cp {
        display: flex; align-items: center; gap: .4rem; padding: 0 .75rem;
        background: #f8fafc; border-right: 2px solid #e2e8f0;
        cursor: pointer; white-space: nowrap; position: relative; min-width: 90px;
    }
    .dark .country-select-cp { background: #1e293b; border-right-color: #475569; }
    .country-select-cp select { position: absolute; inset: 0; opacity: 0; width: 100%; cursor: pointer; }
    .country-flag-cp { font-size: 1.2rem; }
    .country-code-cp { font-weight: 700; font-size: .9rem; color: var(--blue-roi); }
    .phone-local-input-cp {
        flex: 1; padding: 1rem 1.25rem; border: none; outline: none; font-size: 1rem; background: transparent; color: inherit;
    }
    .form-success-hint { color: #10b981; font-size: .82rem; margin-top: .35rem; display: flex; align-items: center; gap: .35rem; }
    
    .btn-primary-cp {
        background: var(--blue-roi); color: white; border: none; border-radius: 15px;
        cursor: pointer; transition: all .3s; display: flex; align-items: center; justify-content: center; gap: .5rem;
    }
    .btn-primary-cp:hover { background: var(--bleu-roi-hover, #3355cc); transform: translateY(-2px); box-shadow: 0 10px 20px rgba(65,105,225,.3); }
</style>

<div class="auth-container">
    <div class="auth-card animate-slide-up">

        {{-- Avatar --}}
        @if(!empty($pending['avatar']))
            <img src="{{ $pending['avatar'] }}" alt="avatar" class="social-avatar">
        @else
            <div class="social-avatar-placeholder">
                {{ strtoupper(substr($pending['name'] ?? '?', 0, 1)) }}
            </div>
        @endif

        <h1 class="auth-title">Bienvenue, {{ explode(' ', $pending['name'])[0] ?? '' }} !</h1>
        <p class="auth-subtitle">
            Connecté avec <strong>{{ $pending['email'] }}</strong><br>
            Choisissez votre rôle et ajoutez votre téléphone pour continuer.
        </p>

        <form method="POST" action="{{ route('auth.complete-profile.save') }}">
            @csrf

            {{-- Role --}}
            <div class="form-group">
                <label class="form-label">{{ __('messages.choose_role') }}</label>
                <div class="role-options">
                    <label class="role-option">
                        <input type="radio" name="role" value="reader" {{ old('role','reader') === 'reader' ? 'checked' : '' }}>
                        <div class="role-card">
                            <div class="role-icon-complete"><i class="fas fa-book-reader"></i></div>
                            <span class="role-name">{{ __('messages.role_reader') ?? 'Lecteur' }}</span>
                            <span class="role-desc-small">{{ __('messages.reader_description') ?? 'Accédez au catalogue.' }}</span>
                        </div>
                    </label>
                    <label class="role-option">
                        <input type="radio" name="role" value="author" {{ old('role') === 'author' ? 'checked' : '' }}>
                        <div class="role-card">
                            <div class="role-icon-complete"><i class="fas fa-pen-fancy"></i></div>
                            <span class="role-name">{{ __('messages.role_author') ?? 'Auteur' }}</span>
                            <span class="role-desc-small">{{ __('messages.author_description') ?? 'Publiez vos œuvres.' }}</span>
                        </div>
                    </label>
                </div>
                @error('role') <p class="form-error">⚠️ {{ $message }}</p> @enderror
            </div>

            {{-- Phone --}}
            <div class="form-group">
                <label class="form-label">📱 {{ __('messages.phone') ?? 'Téléphone' }}</label>
                <input type="hidden" name="phone" id="cpPhoneHidden">
                <div class="phone-group-cp" id="cpPhoneGroup">
                    <div class="country-select-cp">
                        <span class="country-flag-cp" id="cpFlag">🇨🇲</span>
                        <span class="country-code-cp" id="cpCode">+237</span>
                        <span style="font-size:.6rem;color:#94a3b8;">▼</span>
                        <select id="cpCountrySelect" onchange="cpCountryChange()" title="Pays">
                            <option value="+237" data-flag="🇨🇲" selected>🇨🇲 Cameroun (+237)</option>
                            <option value="+33"  data-flag="🇫🇷">🇫🇷 France (+33)</option>
                            <option value="+32"  data-flag="🇧🇪">🇧🇪 Belgique (+32)</option>
                            <option value="+41"  data-flag="🇨🇭">🇨🇭 Suisse (+41)</option>
                            <option value="+1"   data-flag="🇺🇸">🇺🇸 USA (+1)</option>
                            <option value="+44"  data-flag="🇬🇧">🇬🇧 UK (+44)</option>
                            <option value="+234" data-flag="🇳🇬">🇳🇬 Nigéria (+234)</option>
                            <option value="+221" data-flag="🇸🇳">🇸🇳 Sénégal (+221)</option>
                            <option value="+241" data-flag="🇬🇦">🇬🇦 Gabon (+241)</option>
                            <option value="+242" data-flag="🇨🇬">🇨🇬 Congo (+242)</option>
                            <option value="+212" data-flag="🇲🇦">🇲🇦 Maroc (+212)</option>
                        </select>
                    </div>
                    <input type="tel" id="cpPhoneLocal" class="phone-local-input-cp"
                           placeholder="6 57 45 03 14" maxlength="15" oninput="cpPhoneInput()">
                </div>
                <p id="cpPhonePreview" class="form-success-hint" style="display:none;"></p>
                @error('phone') <p class="form-error">⚠️ {{ $message }}</p> @enderror
                <p style="font-size:.78rem;color:#64748b;margin-top:.3rem;">📋 Format Cameroun : <strong>6 57 45 03 14</strong> — 9 chiffres</p>
            </div>

            <button type="submit" class="btn-primary-cp" style="width:100%;padding:1rem;font-size:1.05rem;margin-top:1.5rem">
                <i class="fas fa-check-circle"></i> Terminer l'inscription
            </button>
        </form>
    </div>
</div>

<script>
    const cpRules = {
        '+237': { digits: 9,  prefix: /^[62]/ },
        '+33' : { digits: 9,  prefix: /^[67]/ },
        '+32' : { digits: 9,  prefix: /^[4]/  },
        '+41' : { digits: 9,  prefix: /^[7]/  },
        '+1'  : { digits: 10, prefix: null     },
        '+44' : { digits: 10, prefix: /^[7]/  },
        '+234': { digits: 10, prefix: /^[7-9]/ },
        '+221': { digits: 9,  prefix: null     },
        '+241': { digits: 7,  prefix: null     },
        '+242': { digits: 9,  prefix: null     },
        '+212': { digits: 9,  prefix: /^[6]/  },
    };
    function cpCountryChange() {
        const sel = document.getElementById('cpCountrySelect');
        const opt = sel.options[sel.selectedIndex];
        document.getElementById('cpCode').textContent = opt.value;
        document.getElementById('cpFlag').textContent = opt.dataset.flag;
        cpPhoneInput();
    }
    function cpPhoneInput() {
        const code  = document.getElementById('cpCountrySelect').value;
        const local = document.getElementById('cpPhoneLocal').value.replace(/\D/g, '');
        const full  = local ? code + local : '';
        document.getElementById('cpPhoneHidden').value = full;
        const group   = document.getElementById('cpPhoneGroup');
        const preview = document.getElementById('cpPhonePreview');
        if (!local) { group.className = 'phone-group-cp'; preview.style.display = 'none'; return; }
        const rule  = cpRules[code];
        const ok    = rule ? (local.length === rule.digits && (!rule.prefix || rule.prefix.test(local))) : local.length >= 7;
        group.className = 'phone-group-cp ' + (ok ? 'is-valid' : 'is-invalid');
        preview.style.display = 'flex';
        preview.style.color   = ok ? '#16a34a' : '#ef4444';
        preview.textContent   = ok ? '✅ ' + full : '⚠️ ' + local.length + ' chiffre(s) saisi(s), ' + (rule ? rule.digits : '7+') + ' attendu(s).';
    }
</script>
@endsection
