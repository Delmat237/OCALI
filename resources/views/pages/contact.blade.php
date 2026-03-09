@extends('layouts.app')
@section('title', __('messages.contact_us'))

@section('content')
<div class="section">
    <div class="container" style="max-width: 760px; margin: 0 auto; padding: 4rem 1.5rem;">
        <h1 class="section-title" style="margin-bottom: 0.5rem;">{{ __('messages.contact_us') }}</h1>
        <p style="color: #64748b; margin-bottom: 2.5rem; font-size: 1.05rem;">
            Une question, une suggestion ou une demande de partenariat ? Nous vous répondons dans les 48 h.
        </p>

        @if(session('success'))
            <div class="alert alert-success animate-slide-up" style="margin-bottom: 1.5rem;">
                <i class="fas fa-check-circle"></i> {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-error animate-slide-up" style="margin-bottom: 1.5rem;">
                <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
            </div>
        @endif

        <div class="card" style="padding: 2.5rem; box-shadow: 0 8px 30px rgba(0,0,0,0.07); border-radius: 20px;">
            <form action="{{ route('contact.send') }}" method="POST">
                @csrf
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin-bottom: 1rem;">
                    <div>
                        <label style="display: block; margin-bottom: 0.4rem; font-weight: 600; font-size: 0.9rem; color: #475569;">
                            Nom complet <span style="color: #ef4444;">*</span>
                        </label>
                        <input type="text" name="name" value="{{ old('name') }}" required
                            style="width: 100%; padding: 0.8rem 1rem; border: 2px solid {{ $errors->has('name') ? '#ef4444' : '#e2e8f0' }}; border-radius: 10px; font-size: 0.95rem; transition: border-color 0.2s;"
                            placeholder="Etienne Ndemaze"
                            onfocus="this.style.borderColor='var(--orange-fluo)'" onblur="this.style.borderColor='#e2e8f0'">
                        @error('name')<p style="color:#ef4444;font-size:0.8rem;margin-top:0.3rem;">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label style="display: block; margin-bottom: 0.4rem; font-weight: 600; font-size: 0.9rem; color: #475569;">
                            Adresse email <span style="color: #ef4444;">*</span>
                        </label>
                        <input type="email" name="email" value="{{ old('email') }}" required
                            style="width: 100%; padding: 0.8rem 1rem; border: 2px solid {{ $errors->has('email') ? '#ef4444' : '#e2e8f0' }}; border-radius: 10px; font-size: 0.95rem; transition: border-color 0.2s;"
                            placeholder="vous@domaine.com"
                            onfocus="this.style.borderColor='var(--orange-fluo)'" onblur="this.style.borderColor='#e2e8f0'">
                        @error('email')<p style="color:#ef4444;font-size:0.8rem;margin-top:0.3rem;">{{ $message }}</p>@enderror
                    </div>
                </div>

                <div style="margin-bottom: 1rem;">
                    <label style="display: block; margin-bottom: 0.4rem; font-weight: 600; font-size: 0.9rem; color: #475569;">
                        Sujet
                    </label>
                    <input type="text" name="subject" value="{{ old('subject') }}"
                        style="width: 100%; padding: 0.8rem 1rem; border: 2px solid #e2e8f0; border-radius: 10px; font-size: 0.95rem; transition: border-color 0.2s;"
                        placeholder="Demande de partenariat, question sur un abonnement…"
                        onfocus="this.style.borderColor='var(--orange-fluo)'" onblur="this.style.borderColor='#e2e8f0'">
                </div>

                <div style="margin-bottom: 1.5rem;">
                    <label style="display: block; margin-bottom: 0.4rem; font-weight: 600; font-size: 0.9rem; color: #475569;">
                        Message <span style="color: #ef4444;">*</span>
                    </label>
                    <textarea name="message" rows="6" required
                        style="width: 100%; padding: 0.8rem 1rem; border: 2px solid {{ $errors->has('message') ? '#ef4444' : '#e2e8f0' }}; border-radius: 10px; font-size: 0.95rem; resize: vertical; transition: border-color 0.2s;"
                        placeholder="Décrivez votre demande en détail…"
                        onfocus="this.style.borderColor='var(--orange-fluo)'" onblur="this.style.borderColor='#e2e8f0'">{{ old('message') }}</textarea>
                    @error('message')<p style="color:#ef4444;font-size:0.8rem;margin-top:0.3rem;">{{ $message }}</p>@enderror
                </div>

                <button type="submit" class="btn btn-primary" style="min-width: 160px;">
                    <i class="fas fa-paper-plane"></i>
                    Envoyer le message
                </button>
            </form>
        </div>

        <!-- Info cards -->
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1.5rem; margin-top: 2rem;">
            <div class="card" style="padding: 1.5rem; text-align: center; border-top: 3px solid var(--orange-fluo);">
                <i class="fas fa-phone" style="color: var(--orange-fluo); font-size: 1.5rem; margin-bottom: 0.75rem;"></i>
                <h4 style="margin-bottom: 0.25rem; font-size: 0.95rem;">Téléphone</h4>
                <p style="color:#64748b; font-size: 0.9rem;">(+237) 659461197</p>
            </div>
            <div class="card" style="padding: 1.5rem; text-align: center; border-top: 3px solid var(--blue-roi);">
                <i class="fas fa-envelope" style="color: var(--blue-roi); font-size: 1.5rem; margin-bottom: 0.75rem;"></i>
                <h4 style="margin-bottom: 0.25rem; font-size: 0.95rem;">Email</h4>
                <p style="color:#64748b; font-size: 0.9rem;">ocali597198@gmail.com</p>
            </div>
            <div class="card" style="padding: 1.5rem; text-align: center; border-top: 3px solid #10b981;">
                <i class="fas fa-map-marker-alt" style="color: #10b981; font-size: 1.5rem; margin-bottom: 0.75rem;"></i>
                <h4 style="margin-bottom: 0.25rem; font-size: 0.95rem;">Siège</h4>
                <p style="color:#64748b; font-size: 0.9rem;">Yaoundé, Cameroun</p>
            </div>
        </div>
    </div>
</div>
@endsection
