<!-- Confirmation Modal -->
<div id="confirmationModal" class="modal" tabindex="-1" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; justify-content: center; align-items: center; z-index: 1050; background: rgba(0,0,0,0.5);">
    <div class="modal-dialog" style="background: white; border-radius: 12px; width: 90%; max-width: 500px; padding: 2rem; text-align: center; box-shadow: 0 10px 25px rgba(0,0,0,0.1); position: relative;">
        <button type="button" class="close-modal" style="position: absolute; top: 1rem; right: 1.5rem; background: none; border: none; font-size: 1.5rem; cursor: pointer; color: #64748b;">&times;</button>
        <h2 style="margin-bottom: 1rem; color: #1e293b;">{{ __('messages.confirm_subscription') }}</h2>
        <h3 id="confirmPlanName" style="color: var(--blue-roi); margin-bottom: 0.5rem; font-size: 1.5rem;"></h3>
        <p id="confirmPlanPrice" style="font-size: 2rem; font-weight: 800; margin-bottom: 1.5rem;"></p>
        <button id="proceedPaymentBtn" class="btn btn-primary" style="width: 100%; font-size: 1.1rem;">
            {{ __('messages.proceed_to_payment') }}
        </button>
    </div>
</div>

<!-- Payment Modal -->
<div id="paymentModal" class="modal" tabindex="-1" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; justify-content: center; align-items: center; z-index: 1050; background: rgba(0,0,0,0.5); overflow-y: auto;">
    <div class="modal-dialog" style="background: white; border-radius: 12px; width: 90%; max-width: 600px; padding: 2rem; box-shadow: 0 10px 25px rgba(0,0,0,0.1); position: relative; margin: 2rem auto;">
        <button type="button" class="close-modal" style="position: absolute; top: 1rem; right: 1.5rem; background: none; border: none; font-size: 1.5rem; cursor: pointer; color: #64748b;">&times;</button>
        <h2 style="margin-bottom: 1.5rem; color: #1e293b; text-align: center;">{{ __('messages.payment') }}</h2>
        
        <div style="background: #f8fafc; padding: 1rem; border-radius: 8px; margin-bottom: 1.5rem; text-align: center;">
            <h3 id="payPlanName" style="margin-bottom: 0.25rem; font-size: 1.25rem;"></h3>
            <p id="payPlanPrice" style="font-size: 1.5rem; font-weight: 700; color: var(--blue-roi); margin: 0;"></p>
        </div>

        <!-- Mobile Money (Nokash Widget) -->
        <div style="margin-bottom: 2rem; border-bottom: 1px solid #e2e8f0; padding-bottom: 1.5rem;">
            <h3 style="margin-bottom: 1rem; display: flex; align-items: center; gap: 0.5rem; color: #0f172a; font-size: 1.2rem;"><i class="fas fa-mobile-alt" style="color: #f97316;"></i> Mobile Money</h3>
            
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
                Payer avec Mobile Money
            </button>
        </div>

        <!-- PayMooney (Card/PayPal direct) -->
        <div>
            <h3 style="margin-bottom: 1rem; font-size: 1.2rem;"><i class="fas fa-credit-card" style="color: var(--blue-roi);"></i> {{ __('messages.card_or_paypal') }}</h3>
            <form id="paymooneyForm" action="" method="POST">
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
    document.addEventListener("DOMContentLoaded", function () {
        const confirmationModal = document.getElementById('confirmationModal');
        const paymentModal = document.getElementById('paymentModal');
        const closeModals = document.querySelectorAll('.close-modal');
        
        let currentPlanSlug = null;
        let currentPlanPrice = 0;
        let currentSubscriptionId = null;

        // Open Confirmation Modal
        document.querySelectorAll('.open-subscribe-modal').forEach(button => {
            button.addEventListener('click', function(e) {
                e.preventDefault();
                @auth
                    currentPlanSlug = this.dataset.slug;
                    currentPlanPrice = this.dataset.price;
                    document.getElementById('confirmPlanName').innerText = this.dataset.name;
                    document.getElementById('confirmPlanPrice').innerText = new Intl.NumberFormat('fr-FR').format(currentPlanPrice) + ' XAF';
                    confirmationModal.style.display = 'flex';
                @else
                    window.location.href = "{{ route('register') }}";
                @endauth
            });
        });

        // Close Modals
        closeModals.forEach(btn => {
            btn.addEventListener('click', () => {
                confirmationModal.style.display = 'none';
                paymentModal.style.display = 'none';
            });
        });

        // Click outside modal to close
        window.addEventListener('click', (e) => {
            if (e.target === confirmationModal) confirmationModal.style.display = 'none';
            if (e.target === paymentModal) paymentModal.style.display = 'none';
        });

        // Proceed to Payment (AJAX to create subscription)
        document.getElementById('proceedPaymentBtn').addEventListener('click', function() {
            const btn = this;
            const originalText = btn.innerText;
            btn.innerText = 'Chargement...';
            btn.disabled = true;

            fetch(`/subscribe/${currentPlanSlug}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json, text/javascript, */*; q=0.01',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(res => res.json())
            .then(data => {
                btn.innerText = originalText;
                btn.disabled = false;
                
                if(data.success) {
                    currentSubscriptionId = data.subscription.id;
                    confirmationModal.style.display = 'none';

                    if (data.requires_payment === false) {
                        alert('Abonnement activé avec succès!');
                        window.location.href = "{{ route('dashboard') }}";
                        return;
                    }

                    showPaymentModal(data.plan, data.subscription);
                } else {
                    alert('Erreur: ' + (data.error || 'Impossible de créer l\'abonnement.'));
                }
            })
            .catch(err => {
                btn.innerText = originalText;
                btn.disabled = false;
                console.error(err);
                alert('Une erreur s\'est produite.');
            });
        });

        function showPaymentModal(plan, subscription) {
            document.getElementById('payPlanName').innerText = plan.localized_name || plan.name;
            document.getElementById('payPlanPrice').innerText = new Intl.NumberFormat('fr-FR').format(plan.price) + ' XAF';
            
            // Set up Paymooney Action
            document.getElementById('paymooneyForm').action = `/subscription/payment/${subscription.id}/paymooney`;

            paymentModal.style.display = 'flex';

            // Setup Nokash data
            setupNokash(plan, subscription);
        }

        // --- Nokash Logique ---
        let mykey = "{{ config('services.nokash.widget_key') }}";
        let logoUrl = "{{ asset('images/logo.png') }}";
        let commandeData = {};

        function setupNokash(plan, subscription) {
            @auth
            commandeData = {
                montant: plan.price,
                reference: "SUB-" + subscription.id + "-" + Date.now(),
                description: "Abonnement " + (plan.localized_name || plan.name),
                email: "{{ auth()->user()->email }}",
                nom: "{{ auth()->user()->name }}",
                telephone: ""
            };
            @endauth
        }

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

        const paiementBtn = document.getElementById("paiement-btn");
        if (paiementBtn) {
            paiementBtn.addEventListener("click", function (e) {
                e.preventDefault();
                console.log("Paiement Nokash en attente...");
                paiement(callbackReussite, callbackErreur, mykey, commandeData.montant);
                startNokashWatcher();
            });
        }

        // Nokash callback methods must be global to be called by scriptwidget.js
        window.callbackReussite = function(data) {
            console.log("Paiement Nokash réussi!", data);
            fetch(`/payment/nokash/success/${currentSubscriptionId}`, {
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
        };

        window.callbackErreur = function(data) {
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
        };

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
    });
</script>
@endpush
