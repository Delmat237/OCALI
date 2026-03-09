@extends('layouts.app')
@section('title', 'Guide de publication – OCaLi')

@push('styles')
<style>
.guide-card {
    background: var(--bg-secondary, #f8fafc);
    border: 1px solid var(--border-color, #e2e8f0);
    border-radius: 16px;
    padding: 2rem;
    margin-bottom: 1.5rem;
}
.dark .guide-card {
    background: #1e293b;
    border-color: #334155;
}
.guide-step {
    display: flex;
    gap: 1.25rem;
    align-items: flex-start;
    padding: 1.5rem;
    background: var(--bg-secondary, #f8fafc);
    border: 1px solid var(--border-color, #e2e8f0);
    border-radius: 14px;
    margin-bottom: 1rem;
}
.dark .guide-step { background: #1e293b; border-color: #334155; }
.guide-step-num {
    min-width: 42px; height: 42px;
    border-radius: 50%;
    display: flex; align-items: center; justify-content: center;
    font-weight: 800; font-size: 1.1rem;
    background: var(--gradient-primary);
    color: #fff;
    flex-shrink: 0;
}
.guide-step-title {
    font-weight: 700;
    font-size: 1rem;
    margin-bottom: 0.3rem;
    color: var(--text-primary, #1e293b);
}
.dark .guide-step-title { color: #e2e8f0; }
.guide-step-desc {
    color: var(--text-secondary, #475569);
    font-size: 0.95rem;
    line-height: 1.6;
}
.dark .guide-step-desc { color: #94a3b8; }
.guide-h2 {
    font-size: 1.35rem;
    margin-bottom: 1.25rem;
    color: var(--text-primary, #1e293b);
    display: flex; align-items: center; gap: 0.6rem;
}
.dark .guide-h2 { color: #e2e8f0; }
.guide-body-text {
    color: var(--text-secondary, #475569);
    line-height: 1.8;
}
.dark .guide-body-text { color: #94a3b8; }
.guide-badge {
    display: inline-flex; align-items: center; gap: 0.4rem;
    background: rgba(255,103,0,0.1);
    color: var(--orange-fluo);
    padding: 0.3rem 0.8rem;
    border-radius: 20px;
    font-size: 0.85rem;
    font-weight: 600;
    margin: 0.25rem;
}
.guide-check-list li {
    display: flex; align-items: flex-start; gap: 0.6rem;
    padding: 0.4rem 0;
    color: var(--text-secondary, #475569);
    line-height: 1.6;
}
.dark .guide-check-list li { color: #94a3b8; }
</style>
@endpush

@section('content')
<div class="section" style="padding-top: 80px; padding-bottom: 5rem;">
<div class="container" style="max-width: 860px; margin: 0 auto; padding: 0 1.5rem;">

    <!-- Hero -->
    <div style="text-align: center; margin-bottom: 3.5rem;">
        <span class="guide-badge" style="font-size: 0.9rem; margin-bottom: 1rem;"><i class="fas fa-rocket"></i> Publiez sur OCaLi</span>
        <h1 class="section-title" style="margin-bottom: 0.75rem;">Guide de Publication</h1>
        <p class="guide-body-text" style="font-size: 1.1rem; max-width: 600px; margin: 0 auto;">
            Tout ce que vous devez savoir pour déposer votre œuvre et la faire lire par des milliers de lecteurs camerounais et africains.
        </p>
    </div>

    <!-- Qui peut publier ? -->
    <section style="margin-bottom: 3rem;">
        <h2 class="guide-h2"><i class="fas fa-user-check" style="color: var(--orange-fluo);"></i> Qui peut publier ?</h2>
        <div class="guide-card">
            <p class="guide-body-text" style="margin-bottom: 1.25rem;">
                Tout auteur possédant un compte <strong>Auteur</strong> sur OCaLi peut soumettre une œuvre. Les œuvres acceptées comprennent :
            </p>
            <div style="display: flex; flex-wrap: wrap; gap: 0.5rem; margin-bottom: 1.25rem;">
                <span class="guide-badge"><i class="fas fa-book"></i> Romans & nouvelles</span>
                <span class="guide-badge"><i class="fas fa-flask"></i> Essais scientifiques</span>
                <span class="guide-badge"><i class="fas fa-graduation-cap"></i> Manuels académiques</span>
                <span class="guide-badge"><i class="fas fa-paint-brush"></i> Bandes dessinées</span>
                <span class="guide-badge"><i class="fas fa-newspaper"></i> Revues & magazines</span>
                <span class="guide-badge"><i class="fas fa-landmark"></i> Littérature africaine</span>
            </div>
            <p class="guide-body-text">
                OCaLi valorise en priorité les œuvres reflétant la culture, l'histoire, la science et la créativité camerounaises et africaines.
            </p>
        </div>
    </section>

    <!-- Étapes de soumission -->
    <section style="margin-bottom: 3rem;">
        <h2 class="guide-h2"><i class="fas fa-list-ol" style="color: var(--orange-fluo);"></i> Étapes de soumission</h2>

        <div class="guide-step">
            <div class="guide-step-num">1</div>
            <div>
                <div class="guide-step-title">Créer un compte Auteur</div>
                <div class="guide-step-desc">Inscrivez-vous sur OCaLi en choisissant le rôle <strong>Auteur</strong>. Si vous avez déjà un compte Lecteur, contactez le support pour une migration de rôle.</div>
            </div>
        </div>

        <div class="guide-step">
            <div class="guide-step-num">2</div>
            <div>
                <div class="guide-step-title">Préparer votre fichier</div>
                <div class="guide-step-desc">Exportez votre livre au format <strong>PDF</strong> (taille maximale : 100 Mo). Assurez-vous que le fichier est lisible, bien structuré, et ne contient pas de DRM incompatibles. Préparez également une <strong>image de couverture</strong> (JPG/PNG, min. 600×900 px).</div>
            </div>
        </div>

        <div class="guide-step">
            <div class="guide-step-num">3</div>
            <div>
                <div class="guide-step-title">Remplir la fiche livre</div>
                <div class="guide-step-desc">Depuis <strong>Tableau de bord → Ajouter un livre</strong>, renseignez : titre, description (min. 100 caractères), catégorie, langue, mots-clés, ISBN/DOI si disponible. Une description soignée améliore la visibilité dans la recherche.</div>
            </div>
        </div>

        <div class="guide-step">
            <div class="guide-step-num">4</div>
            <div>
                <div class="guide-step-title">Soumettre pour validation</div>
                <div class="guide-step-desc">Cliquez sur <strong>Soumettre</strong>. Votre livre passe en statut <em>En attente</em>. Notre équipe éditoriale l'examine sous <strong>24 à 48 heures</strong>.</div>
            </div>
        </div>

        <div class="guide-step">
            <div class="guide-step-num">5</div>
            <div>
                <div class="guide-step-title">Publication & visibilité</div>
                <div class="guide-step-desc">Une fois approuvé, votre livre apparaît dans le catalogue OCaLi, dans les résultats de recherche et dans les recommandations. Tous les abonnés newsletter sont notifiés par email.</div>
            </div>
        </div>
    </section>

    <!-- Standards de qualité -->
    <section style="margin-bottom: 3rem;">
        <h2 class="guide-h2"><i class="fas fa-star" style="color: var(--orange-fluo);"></i> Standards de qualité</h2>
        <div class="guide-card">
            <p class="guide-body-text" style="margin-bottom: 1rem;">Pour être accepté, votre livre doit respecter les critères suivants :</p>
            <ul class="guide-check-list" style="list-style: none; padding: 0; margin: 0;">
                <li><i class="fas fa-check-circle" style="color: #10b981; flex-shrink:0;"></i> Contenu original — pas de plagiat ni de reproduction non autorisée</li>
                <li><i class="fas fa-check-circle" style="color: #10b981; flex-shrink:0;"></i> Rédaction soignée (orthographe, syntaxe, mise en page)</li>
                <li><i class="fas fa-check-circle" style="color: #10b981; flex-shrink:0;"></i> Fichier PDF lisible et non corrompu</li>
                <li><i class="fas fa-check-circle" style="color: #10b981; flex-shrink:0;"></i> Couverture attractive et adaptée au contenu</li>
                <li><i class="fas fa-check-circle" style="color: #10b981; flex-shrink:0;"></i> Aucun contenu illégal, haineux ou pornographique</li>
                <li><i class="fas fa-check-circle" style="color: #10b981; flex-shrink:0;"></i> Description honnête et non trompeuse</li>
                <li><i class="fas fa-times-circle" style="color: #ef4444; flex-shrink:0;"></i> Les fichiers protégés par DRM tiers ne sont pas acceptés</li>
                <li><i class="fas fa-times-circle" style="color: #ef4444; flex-shrink:0;"></i> Les contenus déjà publiés en libre accès total sont exclus</li>
            </ul>
        </div>
    </section>

    <!-- Processus de révision -->
    <section style="margin-bottom: 3rem;">
        <h2 class="guide-h2"><i class="fas fa-search" style="color: var(--orange-fluo);"></i> Processus de révision</h2>
        <div class="guide-card">
            <p class="guide-body-text">Notre équipe éditoriale vérifie chaque soumission selon 4 critères :</p>
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); gap: 1rem; margin-top: 1.25rem;">
                @foreach([
                    ['fas fa-file-alt', 'Qualité du fichier', 'Lisibilité, mise en page, absence d\'erreurs majeurs'],
                    ['fas fa-shield-alt', 'Originalité', 'Vérification contre le plagiat'],
                    ['fas fa-eye', 'Conformité', 'Respect des CGU et valeurs d\'OCaLi'],
                    ['fas fa-image', 'Couverture', 'Adéquation avec le contenu'],
                ] as $item)
                <div style="text-align: center; padding: 1rem; background: rgba(255,103,0,0.05); border-radius: 12px;">
                    <i class="fas {{ $item[0] }}" style="font-size: 1.5rem; color: var(--orange-fluo); margin-bottom: 0.5rem; display: block;"></i>
                    <div class="guide-step-title" style="font-size: 0.9rem; margin-bottom: 0.25rem;">{{ $item[1] }}</div>
                    <div class="guide-step-desc" style="font-size: 0.8rem;">{{ $item[2] }}</div>
                </div>
                @endforeach
            </div>
            <p class="guide-body-text" style="margin-top: 1.25rem;">
                En cas de rejet, vous êtes notifié par email avec les motifs détaillés. Vous pouvez corriger et re-soumettre autant de fois que nécessaire.
            </p>
        </div>
    </section>

    <!-- CTA -->
    <div style="text-align: center; padding: 2.5rem; background: linear-gradient(135deg, rgba(255,103,0,0.08), rgba(0,62,105,0.08)); border-radius: 20px; border: 1px solid var(--border-color, #e2e8f0);">
        <h3 class="guide-step-title" style="font-size: 1.3rem; margin-bottom: 0.5rem;">Prêt à publier votre œuvre ?</h3>
        <p class="guide-body-text" style="margin-bottom: 1.5rem;">Rejoignez des auteurs qui valorisent leur talent sur OCaLi.</p>
        <a href="{{ route('register') }}" class="btn btn-primary" style="margin-right: 0.75rem;">
            <i class="fas fa-pen-nib"></i> Devenir Auteur
        </a>
        <a href="{{ route('contact') }}" class="btn btn-outline">
            <i class="fas fa-question-circle"></i> Une question ?
        </a>
    </div>

</div>
</div>
@endsection
