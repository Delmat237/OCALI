@extends('layouts.app')
@section('title', __('messages.terms'))

@section('content')
<div class="section">
    <div class="container" style="max-width: 820px; margin: 0 auto; padding: 4rem 1.5rem;">
        <h1 class="section-title" style="margin-bottom: 0.5rem;">Conditions Générales d'Utilisation</h1>
        <p style="color:#64748b; margin-bottom: 2.5rem;">Version en vigueur — Mars 2026 &middot; OCaLi by BLAKTEC</p>

        <div class="card" style="padding: 2.5rem; line-height: 1.9; color: #475569; box-shadow: 0 4px 20px rgba(0,0,0,0.06); border-radius: 20px;">

            <h2 style="color:#1e293b; font-size:1.2rem; margin-top:0; margin-bottom:0.75rem;">1. Objet</h2>
            <p>Les présentes Conditions Générales d'Utilisation (CGU) régissent l'accès et l'utilisation de la plateforme OCaLi, éditée par BLAKTEC, dont le siège social est établi à Yaoundé, Cameroun. En vous inscrivant ou en accédant à la plateforme, vous acceptez sans réserve les présentes CGU.</p>

            <h2 style="color:#1e293b; font-size:1.2rem; margin-top:1.75rem; margin-bottom:0.75rem;">2. Description du service</h2>
            <p>OCaLi est une bibliothèque numérique permettant :</p>
            <ul style="margin-left: 1.5rem; margin-bottom: 0.75rem;">
                <li>Aux <strong>lecteurs</strong> d'accéder à des œuvres numériques (romans, essais, bandes dessinées, etc.) via des abonnements mensuels, trimestriels ou annuels.</li>
                <li>Aux <strong>auteurs</strong> de publier, vendre et suivre les performances de leurs œuvres.</li>
            </ul>
            <p>OCaLi n'est pas une plateforme de téléchargement. Toute lecture s'effectue exclusivement dans le lecteur intégré en ligne.</p>

            <h2 style="color:#1e293b; font-size:1.2rem; margin-top:1.75rem; margin-bottom:0.75rem;">3. Inscription et compte utilisateur</h2>
            <p>L'inscription est gratuite. Vous vous engagez à fournir des informations exactes, à maintenir votre mot de passe confidentiel et à notifier OCaLi de tout accès non autorisé à votre compte. OCaLi se réserve le droit de suspendre ou supprimer tout compte en cas d'utilisation frauduleuse ou abusive.</p>

            <h2 style="color:#1e293b; font-size:1.2rem; margin-top:1.75rem; margin-bottom:0.75rem;">4. Abonnements et paiements</h2>
            <p>Les abonnements sont payants et non remboursables, sauf disposition légale contraire. Le paiement s'effectue via les opérateurs de Mobile Money (MTN MoMo, Orange Money) ou tout autre moyen proposé par la plateforme. Tout abonnement est activé à réception du paiement confirmé.</p>
            <p>En cas d'expiration sans renouvellement, le lecteur conserve un accès partiel (50 % de ses livres sélectionnés) à titre de rétention.</p>

            <h2 style="color:#1e293b; font-size:1.2rem; margin-top:1.75rem; margin-bottom:0.75rem;">5. Propriété intellectuelle</h2>
            <p>Les œuvres publiées sur OCaLi restent la propriété exclusive de leurs auteurs. OCaLi dispose d'une licence non exclusive pour les afficher dans son lecteur en ligne. Toute reproduction, extraction, capture d'écran ou distribution non autorisée des œuvres est strictement interdite et constitue une contrefaçon passible de poursuites.</p>

            <h2 style="color:#1e293b; font-size:1.2rem; margin-top:1.75rem; margin-bottom:0.75rem;">6. Rémunération des auteurs</h2>
            <p>Les auteurs reçoivent une part des revenus générés par les abonnements lecteurs, calculée selon les taux de commission définis dans leur plan d'abonnement auteur. Le versement est effectué via Mobile Money sur demande explicite de l'auteur, sous réserve du seuil minimal de retrait en vigueur.</p>

            <h2 style="color:#1e293b; font-size:1.2rem; margin-top:1.75rem; margin-bottom:0.75rem;">7. Comportement des utilisateurs</h2>
            <p>Il est interdit d'utiliser OCaLi pour : publier des contenus illégaux, plagier, harceler d'autres utilisateurs, ou tenter de contourner les mesures de protection des œuvres. OCaLi se réserve le droit de retirer tout contenu litigieux sans préavis.</p>

            <h2 style="color:#1e293b; font-size:1.2rem; margin-top:1.75rem; margin-bottom:0.75rem;">8. Limitation de responsabilité</h2>
            <p>OCaLi ne saurait être tenu responsable des interruptions de service, des pertes de données liées à des causes extérieures ou des dommages indirects résultant de l'utilisation de la plateforme.</p>

            <h2 style="color:#1e293b; font-size:1.2rem; margin-top:1.75rem; margin-bottom:0.75rem;">9. Modification des CGU</h2>
            <p>OCaLi se réserve le droit de modifier les présentes CGU à tout moment. Les utilisateurs seront informés par email en cas de changement substantiel. La poursuite de l'utilisation de la plateforme vaut acceptation des nouvelles conditions.</p>

            <h2 style="color:#1e293b; font-size:1.2rem; margin-top:1.75rem; margin-bottom:0.75rem;">10. Droit applicable</h2>
            <p>Les présentes CGU sont régies par le droit camerounais et en application des textes de la CEMAC relatifs au commerce électronique. Tout litige sera soumis à la juridiction compétente de Yaoundé.</p>

            <p style="margin-top: 2rem; padding-top: 1.5rem; border-top: 1px solid #e2e8f0; font-size: 0.9rem; color: #94a3b8;">
                Pour toute question : <a href="{{ route('contact') }}" style="color: var(--orange-fluo);">nous contacter</a> &middot; ocali597198@gmail.com
            </p>
        </div>
    </div>
</div>
@endsection
