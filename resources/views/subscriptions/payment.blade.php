@extends('layouts.app')
@section('title', __('messages.payment'))

@section('content')
<div class="section">
    <div class="container" style="max-width: 600px;">
        <h1 class="section-title" style="margin-bottom: 2rem; text-align: center;">{{ __('messages.payment') }}</h1>

        <div class="card card-elevated" style="padding: 2rem; margin-bottom: 1.5rem;">
            <h3 style="margin-bottom: 0.5rem;">{{ $plan->localized_name }}</h3>
            <p style="font-size: 1.5rem; font-weight: 700; color: var(--blue-roi);">{{ number_format($plan->price, 0, ',', ' ') }} XAF</p>
        </div>

        <!-- Mobile Money (Nokash Widget) -->
        <div class="card card-elevated" style="padding: 2rem; margin-bottom: 1.5rem;">
            <h3 style="margin-bottom: 1.5rem; display: flex; align-items: center; gap: 0.5rem; color: #0f172a; font-size: 1.25rem;"><i class="fas fa-mobile-alt" style="color: #f97316;"></i> Mobile Money</h3>
            
            <div id="payment-form-container">
                <div style="margin-bottom: 1rem;">
                    <label style="display: block; margin-bottom: 0.5rem; font-weight: 600; color: #1e293b;">Mode de paiement</label>
                    <div style="display: flex; gap: 1rem;">
                        <label style="flex: 1; padding: 1rem; border: 2px solid #e2e8f0; border-radius: 12px; cursor: pointer; text-align: center; transition: all 0.2s;" class="payment-option selected" data-method="ORANGE_MONEY">
                            <i class="fas fa-mobile-alt" style="font-size: 1.5rem; color: #f97316; display: block; margin-bottom: 0.25rem;"></i>
                            <span style="color: #334155;">Orange Money</span>
                        </label>
                        <label style="flex: 1; padding: 1rem; border: 2px solid #e2e8f0; border-radius: 12px; cursor: pointer; text-align: center; transition: all 0.2s;" class="payment-option" data-method="MTN_MOMO">
                            <i class="fas fa-mobile-alt" style="font-size: 1.5rem; color: #eab308; display: block; margin-bottom: 0.25rem;"></i>
                            <span style="color: #334155;">MTN MoMo</span>
                        </label>
                    </div>
                </div>

                <button id="paiement-btn" class="btn btn-primary" style="width: 100%; font-weight: 600; padding: 0.75rem; background-color: #f97316; border-color: #f97316; color: white;">
                    Payer maintenant
                </button>
            </div>
        </div>

        <!-- PayMooney (Card/PayPal direct) -->
        <div class="card card-elevated" style="padding: 2rem;">
            <h3 style="margin-bottom: 1rem;"><i class="fas fa-credit-card" style="color: var(--blue-roi);"></i> {{ __('messages.card_or_paypal') }}</h3>
            <form action="{{ route('subscription.paymooney', $subscription) }}" method="POST">
                @csrf
                <button type="submit" class="btn btn-secondary" style="width: 100%;">
                    <i class="fab fa-paypal"></i> {{ __('messages.pay_with_paymooney') }}
                </button>
            </form>
            <p style="color: #94a3b8; font-size: 0.8rem; margin-top: 0.5rem; text-align: center;">{{ __('messages.paymooney_note') }}</p>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://widget.nokash.app/app/views/Widget/js/scriptwidget.js"></script>
<script>
    var mykey = "{{ config('services.nokash.widget_key') }}";
    var logoUrl = "{{ asset('images/logo.png') }}";

    var commandeData = {
        montant: {{ $plan->price }},
        reference: "SUB-{{ $subscription->id }}-{{ time() }}",
        description: "Abonnement {{ $plan->localized_name }}",
        email: "{{ auth()->user()->email }}",
        nom: "{{ auth()->user()->name }}",
        telephone: ""
    };

    document.addEventListener("DOMContentLoaded", function () {
        document.querySelectorAll('.payment-option').forEach(opt => {
            opt.addEventListener('click', function() {
                document.querySelectorAll('.payment-option').forEach(o => {
                    o.style.borderColor = '#e2e8f0';
                    o.classList.remove('selected');
                });
                this.style.borderColor = 'var(--orange-fluo)';
                this.classList.add('selected');
            });
        });

        const firstOption = document.querySelector('.payment-option');
        if(firstOption) {
            firstOption.style.borderColor = 'var(--orange-fluo)';
            firstOption.classList.add('selected');
        }

        const paiementBtn = document.getElementById("paiement-btn");
        if (paiementBtn) {
            paiementBtn.addEventListener("click", function (e) {
                e.preventDefault();
                console.log("Paiement Nokash en attente...");
                paiement(callbackReussite, callbackErreur, mykey, commandeData.montant);
                startNokashWatcher();
            });
        }
    });

    function callbackReussite(data) {
        console.log("Paiement Nokash réussi!", data);
        fetch("{{ route('payment.nokash.success', $subscription) }}", {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify(data)
        }).then(response => {
            window.location.href = "{{ route('checkout.success') }}";
        }).catch(error => {
            console.error("Erreur fetch:", error);
            window.location.href = "{{ route('checkout.success') }}";
        });
    }

    function callbackErreur(data) {
        console.log("Paiement Nokash échoué!", data);
        let errorMessage = "Le paiement a échoué. Veuillez réessayer.";
        
        if (data && data.message && (data.message.statusReason === 'BALANCE_INSUFFICIENT' || data.message.statusReason === 'LOW_BALANCE_OR_PAYEE_LIMIT_REACHED_OR_NOT_ALLOWED')) {
            errorMessage = "Votre solde est insuffisant. Veuillez recharger votre compte et réessayer.";
        } else if (data && data.message && data.message.statusReason === 'TIMEOUT') {
            errorMessage = "La transaction a expiré. Veuillez valider le paiement plus rapidement sur votre téléphone.";
        } else if (data && data.message && data.message.statusReason === 'CANCELLED') {
            errorMessage = "La transaction a été annulée.";
        } else if (data && data.message && data.message.message) {
            errorMessage = "Erreur: " + data.message.message;
        }
        alert(errorMessage);
    }

    function startNokashWatcher() {
        const observer = new MutationObserver((mutations) => {
            if (document.querySelector('.jBox-wrapper')) {
                customizeNokashPopup();
            }
        });
        observer.observe(document.body, { childList: true, subtree: true });
        const interval = setInterval(() => {
            if (document.querySelector('.jBox-wrapper')) { customizeNokashPopup(); }
        }, 500);
        setTimeout(() => { observer.disconnect(); clearInterval(interval); }, 120000);
    }
    
    function customizeNokashPopup() {
        var logoImg = document.getElementById('wd-logo') || document.querySelector('.wd-logo');
        if (logoImg) {
            logoImg.src = logoUrl;
            logoImg.alt = 'OCaLi';
            logoImg.style.width = '50px';
            logoImg.style.marginRight = '10px';
        }

        var titleElement = document.querySelector('.jBox-title h5.title') || document.querySelector('.jBox-title .title');
        if (titleElement) {
            var titleText = titleElement.textContent || titleElement.innerText || '';
            if (titleText.includes('eshop_cart') || titleText.trim() === 'eshop_cart') { titleElement.textContent = 'OCaLi'; }
        }

        var subTitleElement = document.querySelector('.jBox-title small.sub-title') || document.querySelector('.jBox-title .sub-title');
        if (subTitleElement) {
            var subText = subTitleElement.textContent || subTitleElement.innerText || '';
            if (subText.includes('ITIAD') || subText.includes('Sarl')) { subTitleElement.textContent = 'By OCaLi'; }
        }

        var titleContainer = document.querySelector('.jBox-title');
        if (titleContainer) {
            var walker = document.createTreeWalker(titleContainer, NodeFilter.SHOW_TEXT, null, false);
            var node;
            while (node = walker.nextNode()) {
                var text = node.textContent;
                if (text && text.includes('eshop_cart')) { node.textContent = text.replace(/eshop_cart/gi, 'OCaLi'); }
                if (text && (text.includes('ITIAD Sarl') || text.includes('ITIAD'))) { node.textContent = text.replace(/ITIAD Sarl/gi, 'By OCaLi').replace(/ITIAD/gi, 'By OCaLi'); }
            }
        }
    }
</script>
@endpush
@endsection
