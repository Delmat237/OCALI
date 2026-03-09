@extends('layouts.app')
@section('title', __('messages.help_center'))

@push('styles')
<style>
/* ── Help page ── */
.help-card {
    background: var(--bg-card, #fff);
    border-radius: 14px;
    overflow: hidden;
    margin-bottom: 0.75rem;
    border: 1px solid var(--border-color, #e2e8f0);
}
.dark .help-card { background: #1e293b; border-color: #334155; }

.help-card summary {
    padding: 1.1rem 1.5rem;
    cursor: pointer;
    font-weight: 600;
    color: var(--text-primary, #1e293b);
    list-style: none;
    display: flex;
    justify-content: space-between;
    align-items: center;
}
.dark .help-card summary { color: #e2e8f0; }

.help-card .faq-body {
    padding: 0 1.5rem 1.25rem;
    color: var(--text-secondary, #475569);
    line-height: 1.7;
}
.dark .help-card .faq-body { color: #94a3b8; }

.help-chevron { color: #94a3b8; transition: transform 0.3s; }

.help-quicklink {
    padding: 1.5rem;
    text-align: center;
    text-decoration: none;
    border-radius: 14px;
    background: var(--bg-card, #fff);
    border: 1px solid var(--border-color, #e2e8f0);
    transition: transform 0.2s;
    display: block;
}
.dark .help-quicklink { background: #1e293b; border-color: #334155; }
.help-quicklink:hover { transform: translateY(-4px); }
.help-quicklink span { font-weight: 700; font-size: 0.95rem; color: var(--text-primary, #1e293b); }
.dark .help-quicklink span { color: #e2e8f0; }

.help-section-title {
    font-size: 1.4rem;
    margin-bottom: 1.25rem;
    display: flex;
    align-items: center;
    gap: 0.75rem;
}

.help-subtitle { color: #64748b; }
.dark .help-subtitle { color: #94a3b8; }

#helpSearch {
    width: 100%;
    padding: 1rem 1rem 1rem 3.25rem;
    border: 2px solid var(--border-color, #e2e8f0);
    border-radius: 14px;
    font-size: 1rem;
    outline: none;
    transition: border-color 0.2s;
    background: var(--bg-card, #fff);
    color: var(--text-primary, #1e293b);
}
.dark #helpSearch { background: #1e293b; color: #e2e8f0; border-color: #334155; }
.dark #helpSearch::placeholder { color: #64748b; }

.help-cta {
    padding: 2rem;
    text-align: center;
    background: linear-gradient(135deg, rgba(255,103,0,0.05) 0%, rgba(0,62,105,0.05) 100%);
    border: 1px solid var(--border-color, #e2e8f0);
    border-radius: 16px;
}
.help-cta h3 { margin-bottom: 0.5rem; color: var(--text-primary, #1e293b); }
.dark .help-cta h3 { color: #e2e8f0; }
.help-cta p { color: #64748b; margin-bottom: 1.5rem; }
.dark .help-cta p { color: #94a3b8; }
</style>
@endpush

@section('content')
<div class="section" style="padding-top: 80px; padding-bottom: 4rem;">
    <div class="container" style="max-width: 900px; margin: 0 auto; padding: 0 1.5rem;">

        <h1 class="section-title" style="margin-bottom: 0.5rem;">{{ __('messages.help_center') }}</h1>
        <p class="help-subtitle" style="margin-bottom: 3rem; font-size: 1.05rem;">
            Trouvez rapidement des réponses à vos questions sur OCaLi.
        </p>

        <!-- Search bar -->
        <div style="position: relative; margin-bottom: 3rem;">
            <input type="text" id="helpSearch" placeholder="Rechercher dans l'aide…">
            <i class="fas fa-search" style="position: absolute; left: 1.1rem; top: 50%; transform: translateY(-50%); color: #94a3b8;"></i>
        </div>

        <!-- Quick Links -->
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(190px, 1fr)); gap: 1.25rem; margin-bottom: 3rem;">
            <a href="#lecteurs" class="help-quicklink" style="border-top: 3px solid var(--orange-fluo);">
                <i class="fas fa-book-open" style="font-size: 2rem; color: var(--orange-fluo); margin-bottom: 0.75rem; display: block;"></i>
                <span>Pour les Lecteurs</span>
            </a>
            <a href="#auteurs" class="help-quicklink" style="border-top: 3px solid var(--blue-roi);">
                <i class="fas fa-pen-nib" style="font-size: 2rem; color: var(--blue-roi); margin-bottom: 0.75rem; display: block;"></i>
                <span>Pour les Auteurs</span>
            </a>
            <a href="#abonnements" class="help-quicklink" style="border-top: 3px solid #10b981;">
                <i class="fas fa-crown" style="font-size: 2rem; color: #10b981; margin-bottom: 0.75rem; display: block;"></i>
                <span>Abonnements</span>
            </a>
            <a href="#paiements" class="help-quicklink" style="border-top: 3px solid #f59e0b;">
                <i class="fas fa-mobile-alt" style="font-size: 2rem; color: #f59e0b; margin-bottom: 0.75rem; display: block;"></i>
                <span>Paiements</span>
            </a>
        </div>

        <!-- Lecteurs -->
        <section id="lecteurs" style="margin-bottom: 3rem;">
            <h2 class="help-section-title" style="color: var(--orange-fluo);">
                <i class="fas fa-book-open"></i> Pour les Lecteurs
            </h2>

            <details class="help-card faq-item">
                <summary>Comment m'inscrire sur OCaLi ?<i class="fas fa-chevron-down help-chevron"></i></summary>
                <div class="faq-body">Cliquer sur <strong>S'inscrire</strong> en haut à droite, choisir le rôle <strong>Lecteur</strong>, renseigner votre nom, email et mot de passe. Vous pouvez aussi vous connecter directement via <strong>Google</strong> ou <strong>Facebook</strong> en un clic.</div>
            </details>

            <details class="help-card faq-item">
                <summary>Comment ajouter un livre à ma bibliothèque ?<i class="fas fa-chevron-down help-chevron"></i></summary>
                <div class="faq-body">Avec un abonnement actif, accédez à la fiche d'un livre puis cliquez sur <strong>Lire maintenant</strong>. Le livre est automatiquement ajouté à votre bibliothèque et décompté de votre quota mensuel.</div>
            </details>

            <details class="help-card faq-item">
                <summary>Que se passe-t-il si mon abonnement expire ?<i class="fas fa-chevron-down help-chevron"></i></summary>
                <div class="faq-body">Vous conservez un accès partiel : <strong>50 % des livres</strong> déjà sélectionnés restent accessibles (les premiers ajoutés). Pour retrouver l'accès complet, renouvelez depuis <a href="{{ route('pricing') }}" style="color: var(--orange-fluo);">Tarifs</a>.</div>
            </details>

            <details class="help-card faq-item">
                <summary>Comment fonctionne le lecteur PDF ?<i class="fas fa-chevron-down help-chevron"></i></summary>
                <div class="faq-body">La lecture se fait dans le navigateur via PDF.js. Vous pouvez naviguer page par page, zoomer/dézoomer et faire défiler. Votre progression est sauvegardée automatiquement toutes les minutes.</div>
            </details>

            <details class="help-card faq-item">
                <summary>Comment ajouter un marque-page ?<i class="fas fa-chevron-down help-chevron"></i></summary>
                <div class="faq-body">Dans le lecteur, cliquer sur l'icône <i class="fas fa-bookmark" style="color: var(--orange-fluo);"></i> en bas à gauche. La page courante est enregistrée. Cliquer sur un marque-page existant pour y revenir directement.</div>
            </details>

            <details class="help-card faq-item">
                <summary>Puis-je télécharger les livres ?<i class="fas fa-chevron-down help-chevron"></i></summary>
                <div class="faq-body">Non. OCaLi est une bibliothèque en ligne ; la lecture se fait exclusivement dans le lecteur intégré pour protéger les droits des auteurs. Les captures d'écran et impressions sont également bloquées.</div>
            </details>
        </section>

        <!-- Auteurs -->
        <section id="auteurs" style="margin-bottom: 3rem;">
            <h2 class="help-section-title" style="color: var(--blue-roi);">
                <i class="fas fa-pen-nib"></i> Pour les Auteurs
            </h2>

            <details class="help-card faq-item">
                <summary>Comment publier mon livre sur OCaLi ?<i class="fas fa-chevron-down help-chevron"></i></summary>
                <div class="faq-body">Inscrivez-vous comme <strong>Auteur</strong>, puis depuis votre tableau de bord cliquez sur <strong>Ajouter un livre</strong>. Uploadez votre fichier PDF (max 100 Mo) et une couverture. Votre livre sera soumis à validation (délai : 24–48 h).</div>
            </details>

            <details class="help-card faq-item">
                <summary>Comment suis-je rémunéré ?<i class="fas fa-chevron-down help-chevron"></i></summary>
                <div class="faq-body">Chaque lecture de votre livre génère des revenus crédités automatiquement à votre <strong>Wallet OCaLi</strong>. Le taux de commission est défini par votre plan d'abonnement auteur.</div>
            </details>

            <details class="help-card faq-item">
                <summary>Comment retirer mes gains ?<i class="fas fa-chevron-down help-chevron"></i></summary>
                <div class="faq-body">Dans votre tableau de bord, allez dans <strong>Portefeuille → Retirer</strong>. Choisissez le montant et votre numéro MTN MoMo ou Orange Money. Le virement est traité sous 48 h. Un seuil minimum de retrait s'applique.</div>
            </details>

            <details class="help-card faq-item">
                <summary>Mon livre a été rejeté, que faire ?<i class="fas fa-chevron-down help-chevron"></i></summary>
                <div class="faq-body">Le message de rejet indique la raison. Corrigez les points soulevés (contenu, format, qualité) puis re-soumettez depuis votre espace auteur. Pour contester, <a href="{{ route('contact') }}" style="color: var(--orange-fluo);">contactez-nous</a>.</div>
            </details>
        </section>

        <!-- Abonnements -->
        <section id="abonnements" style="margin-bottom: 3rem;">
            <h2 class="help-section-title" style="color: #10b981;">
                <i class="fas fa-crown"></i> Abonnements
            </h2>

            <details class="help-card faq-item">
                <summary>Quels sont les plans disponibles ?<i class="fas fa-chevron-down help-chevron"></i></summary>
                <div class="faq-body">Plans <strong>Lecteur</strong> (mensuel, trimestriel, annuel) et plans <strong>Auteur</strong>. Consultez la page <a href="{{ route('pricing') }}" style="color: var(--orange-fluo);">Tarifs</a> pour les détails et prix.</div>
            </details>

            <details class="help-card faq-item">
                <summary>Les abonnements se renouvellent-ils automatiquement ?<i class="fas fa-chevron-down help-chevron"></i></summary>
                <div class="faq-body">Non. OCaLi utilise un modèle de renouvellement manuel via Mobile Money. Vous recevrez un rappel par email quelques jours avant l'expiration.</div>
            </details>

            <details class="help-card faq-item">
                <summary>Les abonnements sont-ils remboursables ?<i class="fas fa-chevron-down help-chevron"></i></summary>
                <div class="faq-body">En règle générale, les abonnements ne sont pas remboursables une fois activés. En cas de problème technique imputable à OCaLi, <a href="{{ route('contact') }}" style="color: var(--orange-fluo);">contactez notre support</a>.</div>
            </details>
        </section>

        <!-- Paiements -->
        <section id="paiements" style="margin-bottom: 3rem;">
            <h2 class="help-section-title" style="color: #f59e0b;">
                <i class="fas fa-mobile-alt"></i> Paiements
            </h2>

            <details class="help-card faq-item">
                <summary>Quels modes de paiement sont acceptés ?<i class="fas fa-chevron-down help-chevron"></i></summary>
                <div class="faq-body">OCaLi accepte <strong>MTN Mobile Money</strong> et <strong>Orange Money</strong> via les plateformes Nokash et PayMooney.</div>
            </details>

            <details class="help-card faq-item">
                <summary>Mon paiement est bloqué, que faire ?<i class="fas fa-chevron-down help-chevron"></i></summary>
                <div class="faq-body">Vérifiez votre solde Mobile Money, le numéro saisi et que le code USSD a bien été validé. Si le problème persiste, <a href="{{ route('contact') }}" style="color: var(--orange-fluo);">contactez-nous</a> en précisant votre email et le montant tenté.</div>
            </details>

            <details class="help-card faq-item">
                <summary>Mon abonnement n'est pas activé après le paiement ?<i class="fas fa-chevron-down help-chevron"></i></summary>
                <div class="faq-body">L'activation est normalement instantanée. Si après 5 minutes votre abonnement n'est toujours pas actif, <a href="{{ route('contact') }}" style="color: var(--orange-fluo);">contactez-nous</a> avec votre référence de transaction.</div>
            </details>
        </section>

        <!-- CTA -->
        <div class="help-cta">
            <i class="fas fa-headset" style="font-size: 2rem; color: var(--orange-fluo); margin-bottom: 0.75rem; display: block;"></i>
            <h3>Vous n'avez pas trouvé votre réponse ?</h3>
            <p>Notre équipe est disponible pour vous aider.</p>
            <a href="{{ route('contact') }}" class="btn btn-primary">
                <i class="fas fa-envelope"></i> Contacter le support
            </a>
        </div>

    </div>
</div>

@push('scripts')
<script>
document.getElementById('helpSearch').addEventListener('input', function() {
    const q = this.value.toLowerCase();
    document.querySelectorAll('.faq-item').forEach(item => {
        item.style.display = item.textContent.toLowerCase().includes(q) ? '' : 'none';
    });
});

document.querySelectorAll('.faq-item').forEach(item => {
    item.addEventListener('toggle', function() {
        const icon = this.querySelector('.help-chevron');
        if (icon) icon.style.transform = this.open ? 'rotate(180deg)' : 'rotate(0)';
    });
});
</script>
@endpush
@endsection
