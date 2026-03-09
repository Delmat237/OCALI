@extends('layouts.app')
@section('title', __('messages.privacy'))

@section('content')
<div class="section">
    <div class="container" style="max-width: 820px; margin: 0 auto; padding: 4rem 1.5rem;">
        <h1 class="section-title" style="margin-bottom: 0.5rem;">Politique de Confidentialité</h1>
        <p style="color:#64748b; margin-bottom: 2.5rem;">Version en vigueur — Mars 2026 &middot; OCaLi by BLAKTEC</p>

        <div class="card" style="padding: 2.5rem; line-height: 1.9; color: #475569; box-shadow: 0 4px 20px rgba(0,0,0,0.06); border-radius: 20px;">

            <h2 style="color:#1e293b; font-size:1.2rem; margin-top:0; margin-bottom:0.75rem;">1. Responsable du traitement</h2>
            <p>BLAKTEC, éditeur de la plateforme OCaLi, est responsable du traitement de vos données personnelles. Siège social : Yaoundé, Cameroun. Contact DPO : <a href="mailto:ocali597198@gmail.com" style="color:var(--orange-fluo);">ocali597198@gmail.com</a>.</p>

            <h2 style="color:#1e293b; font-size:1.2rem; margin-top:1.75rem; margin-bottom:0.75rem;">2. Données collectées</h2>
            <p>Lors de l'utilisation d'OCaLi, nous collectons :</p>
            <ul style="margin-left: 1.5rem; margin-bottom: 0.75rem;">
                <li><strong>Données d'inscription :</strong> nom, adresse email, rôle (lecteur/auteur), numéro de téléphone.</li>
                <li><strong>Données de lecture :</strong> titres consultés, pages lues, durée de session, signets.</li>
                <li><strong>Données financières :</strong> historique des abonnements, transactions du wallet, demandes de retrait.</li>
                <li><strong>Données techniques :</strong> adresse IP, type de navigateur, logs d'accès (à des fins de sécurité).</li>
            </ul>
            <p>Les données de carte bancaire ne sont pas stockées sur OCaLi ; les paiements sont traités par des opérateurs tiers (Nokash, PayMooney).</p>

            <h2 style="color:#1e293b; font-size:1.2rem; margin-top:1.75rem; margin-bottom:0.75rem;">3. Finalités du traitement</h2>
            <p>Vos données sont utilisées pour :</p>
            <ul style="margin-left: 1.5rem; margin-bottom: 0.75rem;">
                <li>Gérer votre compte et vous fournir l'accès aux œuvres.</li>
                <li>Traiter les paiements et la rémunération des auteurs.</li>
                <li>Améliorer nos services via des statistiques agrégées anonymisées.</li>
                <li>Vous adresser des newsletters et informations produit (avec votre consentement).</li>
                <li>Assurer la sécurité de la plateforme et prévenir les fraudes.</li>
            </ul>

            <h2 style="color:#1e293b; font-size:1.2rem; margin-top:1.75rem; margin-bottom:0.75rem;">4. Base légale</h2>
            <p>Le traitement de vos données repose sur : l'exécution du contrat (inscription, abonnement), le consentement (newsletters), l'intérêt légitime (sécurité, statistiques) et les obligations légales.</p>

            <h2 style="color:#1e293b; font-size:1.2rem; margin-top:1.75rem; margin-bottom:0.75rem;">5. Partage des données</h2>
            <p>Vos données ne sont pas vendues à des tiers. Elles peuvent être partagées avec :</p>
            <ul style="margin-left: 1.5rem; margin-bottom: 0.75rem;">
                <li>Les prestataires de paiement (Nokash, PayMooney) pour traiter les transactions.</li>
                <li>Les hébergeurs de l'infrastructure technique (serveurs sécurisés sous contrat de confidentialité).</li>
                <li>Les autorités compétentes en cas d'obligation légale.</li>
            </ul>

            <h2 style="color:#1e293b; font-size:1.2rem; margin-top:1.75rem; margin-bottom:0.75rem;">6. Durée de conservation</h2>
            <p>Les données de compte sont conservées pendant toute la durée de la relation contractuelle, puis archivées pendant 3 ans. Les logs techniques sont conservés 12 mois. Les données financières sont conservées 10 ans conformément aux obligations comptables.</p>

            <h2 style="color:#1e293b; font-size:1.2rem; margin-top:1.75rem; margin-bottom:0.75rem;">7. Vos droits</h2>
            <p>Conformément à la législation en vigueur, vous disposez des droits suivants :</p>
            <ul style="margin-left: 1.5rem; margin-bottom: 0.75rem;">
                <li><strong>Accès</strong> : obtenir une copie de vos données.</li>
                <li><strong>Rectification</strong> : corriger des données inexactes.</li>
                <li><strong>Suppression</strong> : demander l'effacement de vos données (depuis votre profil ou par email).</li>
                <li><strong>Opposition</strong> : vous opposer au traitement pour des finalités marketing.</li>
                <li><strong>Portabilité</strong> : recevoir vos données dans un format structuré.</li>
            </ul>
            <p>Pour exercer ces droits : <a href="mailto:ocali597198@gmail.com" style="color:var(--orange-fluo);">ocali597198@gmail.com</a>.</p>

            <h2 style="color:#1e293b; font-size:1.2rem; margin-top:1.75rem; margin-bottom:0.75rem;">8. Cookies</h2>
            <p>OCaLi utilise des cookies de session (strictement nécessaires) pour maintenir votre connexion et mémoriser vos préférences (thème, langue). Aucun cookie publicitaire tiers n'est utilisé.</p>

            <h2 style="color:#1e293b; font-size:1.2rem; margin-top:1.75rem; margin-bottom:0.75rem;">9. Sécurité</h2>
            <p>OCaLi met en œuvre des mesures techniques et organisationnelles adaptées pour protéger vos données : chiffrement des mots de passe (bcrypt), HTTPS, journalisation des accès critiques et filigranage des lectures.</p>

            <h2 style="color:#1e293b; font-size:1.2rem; margin-top:1.75rem; margin-bottom:0.75rem;">10. Modifications</h2>
            <p>Toute modification substantielle de la présente politique sera notifiée par email aux utilisateurs. La date de dernière mise à jour apparaît en entête de cette page.</p>

            <p style="margin-top: 2rem; padding-top: 1.5rem; border-top: 1px solid #e2e8f0; font-size: 0.9rem; color: #94a3b8;">
                Pour toute question : <a href="{{ route('contact') }}" style="color: var(--orange-fluo);">nous contacter</a> &middot; ocali597198@gmail.com
            </p>
        </div>
    </div>
</div>
@endsection
