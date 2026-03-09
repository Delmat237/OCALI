@extends('layouts.app')
@section('title', 'Royalties & Gains auteurs – OCaLi')

@push('styles')
<style>
.roy-card {
    background: var(--bg-secondary, #f8fafc);
    border: 1px solid var(--border-color, #e2e8f0);
    border-radius: 16px;
    padding: 2rem;
    margin-bottom: 1.5rem;
}
.dark .roy-card { background: #1e293b; border-color: #334155; }

.roy-h2 {
    font-size: 1.35rem;
    margin-bottom: 1.25rem;
    color: var(--text-primary, #1e293b);
    display: flex; align-items: center; gap: 0.6rem;
}
.dark .roy-h2 { color: #e2e8f0; }

.roy-body {
    color: var(--text-secondary, #475569);
    line-height: 1.8;
}
.dark .roy-body { color: #94a3b8; }

.roy-stat {
    text-align: center;
    padding: 1.5rem;
    background: rgba(255,103,0,0.06);
    border: 1px solid rgba(255,103,0,0.15);
    border-radius: 14px;
}
.roy-stat-value {
    font-size: 2.2rem;
    font-weight: 800;
    background: var(--gradient-primary);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
    display: block;
    line-height: 1.1;
    margin-bottom: 0.4rem;
}
.roy-stat-label {
    color: var(--text-secondary, #64748b);
    font-size: 0.9rem;
}
.dark .roy-stat-label { color: #94a3b8; }

.roy-step {
    display: flex;
    gap: 1rem;
    align-items: flex-start;
    padding: 1.1rem 0;
    border-bottom: 1px solid var(--border-color, #f1f5f9);
}
.dark .roy-step { border-bottom-color: #334155; }
.roy-step:last-child { border-bottom: none; }
.roy-step-icon {
    min-width: 38px; height: 38px;
    border-radius: 50%;
    display: flex; align-items: center; justify-content: center;
    background: rgba(255,103,0,0.1);
    color: var(--orange-fluo);
    font-size: 1rem;
    flex-shrink: 0;
}
.roy-step-title {
    font-weight: 700;
    color: var(--text-primary, #1e293b);
    margin-bottom: 0.25rem;
    font-size: 0.95rem;
}
.dark .roy-step-title { color: #e2e8f0; }
.roy-step-desc { color: var(--text-secondary, #475569); font-size: 0.9rem; line-height: 1.55; }
.dark .roy-step-desc { color: #94a3b8; }

/* Plan table */
.plan-table { width: 100%; border-collapse: collapse; font-size: 0.95rem; }
.plan-table th {
    padding: 0.85rem 1rem;
    text-align: left;
    background: rgba(255,103,0,0.07);
    color: var(--text-primary, #1e293b);
    font-weight: 700;
}
.dark .plan-table th { color: #e2e8f0; background: rgba(255,103,0,0.12); }
.plan-table td {
    padding: 0.85rem 1rem;
    border-top: 1px solid var(--border-color, #e2e8f0);
    color: var(--text-secondary, #475569);
}
.dark .plan-table td { border-color: #334155; color: #94a3b8; }

/* Simulator */
#sim-result {
    color: var(--text-primary, #1e293b);
}
.dark #sim-result { color: #e2e8f0; }
.sim-input {
    width: 100%; padding: 0.75rem 1rem;
    border: 2px solid var(--border-color, #e2e8f0);
    border-radius: 10px;
    background: transparent;
    color: var(--text-primary, #1e293b);
    font-size: 0.95rem;
    outline: none;
}
.dark .sim-input { border-color: #334155; color: #e2e8f0; }
.sim-label { font-weight: 600; font-size: 0.9rem; color: var(--text-secondary, #475569); margin-bottom: 0.4rem; display: block; }
.dark .sim-label { color: #94a3b8; }
</style>
@endpush

@section('content')
<div class="section" style="padding-top: 80px; padding-bottom: 5rem;">
<div class="container" style="max-width: 860px; margin: 0 auto; padding: 0 1.5rem;">

    <!-- Hero -->
    <div style="text-align: center; margin-bottom: 3.5rem;">
        <span style="display: inline-flex; align-items: center; gap: 0.4rem; background: rgba(255,103,0,0.1); color: var(--orange-fluo); padding: 0.3rem 0.9rem; border-radius: 20px; font-size: 0.9rem; font-weight: 600; margin-bottom: 1rem;">
            <i class="fas fa-coins"></i> Rémunération Auteurs
        </span>
        <h1 class="section-title" style="margin-bottom: 0.75rem;">Royalties & Gains</h1>
        <p class="roy-body" style="font-size: 1.1rem; max-width: 600px; margin: 0 auto;">
            OCaLi rémunère équitablement chaque auteur à chaque lecture. Découvrez comment fonctionnent vos gains et comment les percevoir.
        </p>
    </div>

    <!-- Stats -->
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(160px, 1fr)); gap: 1.25rem; margin-bottom: 3rem;">
        <div class="roy-stat">
            <span class="roy-stat-value">70%</span>
            <span class="roy-stat-label">Reversé aux auteurs (plan Pro)</span>
        </div>
        <div class="roy-stat">
            <span class="roy-stat-value">48h</span>
            <span class="roy-stat-label">Délai de virement moyen</span>
        </div>
        <div class="roy-stat">
            <span class="roy-stat-value">0 XAF</span>
            <span class="roy-stat-label">Frais d'inscription auteur</span>
        </div>
        <div class="roy-stat">
            <span class="roy-stat-value">∞</span>
            <span class="roy-stat-label">Livres publiables (plan illimité)</span>
        </div>
    </div>

    <!-- Comment ça marche -->
    <section style="margin-bottom: 3rem;">
        <h2 class="roy-h2"><i class="fas fa-cog" style="color: var(--orange-fluo);"></i> Comment sont calculés vos gains ?</h2>
        <div class="roy-card">
            <p class="roy-body" style="margin-bottom: 1.25rem;">
                Chaque fois qu'un lecteur abonné ajoute votre livre à sa bibliothèque, <strong>une part du revenu de son abonnement vous est reversée</strong>, proportionnellement au taux de commission de votre plan auteur.
            </p>
            <div class="roy-step">
                <div class="roy-step-icon"><i class="fas fa-user-plus"></i></div>
                <div>
                    <div class="roy-step-title">1. Un lecteur sélectionne votre livre</div>
                    <div class="roy-step-desc">Le livre est comptabilisé dans son quota mensuel. La transaction est enregistrée dans votre rapport de gains.</div>
                </div>
            </div>
            <div class="roy-step">
                <div class="roy-step-icon"><i class="fas fa-calculator"></i></div>
                <div>
                    <div class="roy-step-title">2. Calcul automatique de la commission</div>
                    <div class="roy-step-desc">La part revenant à OCaLi (commission plateforme) est déduite. Le reste est crédité instantanément à votre Wallet.</div>
                </div>
            </div>
            <div class="roy-step">
                <div class="roy-step-icon"><i class="fas fa-wallet"></i></div>
                <div>
                    <div class="roy-step-title">3. Crédit instantané au Wallet</div>
                    <div class="roy-step-desc">Votre solde est mis à jour en temps réel. Vous pouvez consulter chaque transaction depuis <strong>Tableau de bord → Portefeuille</strong>.</div>
                </div>
            </div>
            <div class="roy-step">
                <div class="roy-step-icon"><i class="fas fa-mobile-alt"></i></div>
                <div>
                    <div class="roy-step-title">4. Retrait vers votre Mobile Money</div>
                    <div class="roy-step-desc">Dès que votre solde atteint le seuil minimum, vous pouvez demander un virement vers MTN MoMo ou Orange Money.</div>
                </div>
            </div>
        </div>
    </section>

    <!-- Taux par plan -->
    <section style="margin-bottom: 3rem;">
        <h2 class="roy-h2"><i class="fas fa-table" style="color: var(--orange-fluo);"></i> Taux de reversement par plan</h2>
        <div class="roy-card" style="padding: 0; overflow: hidden;">
            <table class="plan-table">
                <thead>
                    <tr>
                        <th>Plan Auteur</th>
                        <th>Durée</th>
                        <th>Publications</th>
                        <th>Taux reversé</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td><strong>Starter</strong></td>
                        <td>Mensuel</td>
                        <td>3 livres</td>
                        <td><strong style="color: var(--orange-fluo);">50 %</strong></td>
                    </tr>
                    <tr>
                        <td><strong>Pro</strong></td>
                        <td>Trimestriel</td>
                        <td>10 livres</td>
                        <td><strong style="color: var(--orange-fluo);">65 %</strong></td>
                    </tr>
                    <tr>
                        <td><strong>Expert</strong></td>
                        <td>Annuel</td>
                        <td>Illimité</td>
                        <td><strong style="color: var(--orange-fluo);">70 %</strong></td>
                    </tr>
                </tbody>
            </table>
        </div>
        <p class="roy-body" style="font-size: 0.88rem; margin-top: 0.75rem;">
            <i class="fas fa-info-circle" style="color: var(--orange-fluo);"></i>
            Les taux exacts peuvent varier selon les offres promotionnelles. Consultez la page <a href="{{ route('pricing') }}" style="color: var(--orange-fluo);">Tarifs</a> pour les valeurs en vigueur.
        </p>
    </section>

    <!-- Simulateur -->
    <section style="margin-bottom: 3rem;">
        <h2 class="roy-h2"><i class="fas fa-calculator" style="color: var(--orange-fluo);"></i> Simulateur de gains</h2>
        <div class="roy-card">
            <p class="roy-body" style="margin-bottom: 1.5rem;">Estimez vos revenus mensuels selon le nombre de lectures et votre plan.</p>
            <div style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 1rem; margin-bottom: 1.25rem;">
                <div>
                    <label class="sim-label">Lectures / mois</label>
                    <input type="number" id="sim-reads" class="sim-input" value="100" min="1" max="100000">
                </div>
                <div>
                    <label class="sim-label">Prix moyen abonnement (XAF)</label>
                    <input type="number" id="sim-price" class="sim-input" value="3000" min="500">
                </div>
                <div>
                    <label class="sim-label">Votre taux (%)</label>
                    <input type="number" id="sim-rate" class="sim-input" value="65" min="1" max="100">
                </div>
            </div>
            <div id="sim-result" style="padding: 1.5rem; background: rgba(255,103,0,0.07); border-radius: 12px; text-align: center;">
                <div style="font-size: 0.9rem; margin-bottom: 0.25rem; color: #64748b;" id="sim-formula">—</div>
                <div style="font-size: 2rem; font-weight: 800;" id="sim-value">—</div>
                <div style="font-size: 0.85rem; color: #94a3b8;">estimation mensuelle</div>
            </div>
        </div>
    </section>

    <!-- Retrait -->
    <section style="margin-bottom: 3rem;">
        <h2 class="roy-h2"><i class="fas fa-money-bill-wave" style="color: var(--orange-fluo);"></i> Percevoir vos gains</h2>
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(240px, 1fr)); gap: 1.25rem;">
            <div class="roy-card" style="border-top: 3px solid #f59e0b;">
                <i class="fas fa-hand-holding-usd" style="font-size: 1.5rem; color: #f59e0b; margin-bottom: 0.75rem; display: block;"></i>
                <div class="roy-step-title">Seuil minimum</div>
                <div class="roy-step-desc" style="margin-top: 0.3rem;">Un solde minimum configurable (par défaut 5 000 XAF) est requis avant de pouvoir effectuer un retrait.</div>
            </div>
            <div class="roy-card" style="border-top: 3px solid #10b981;">
                <i class="fas fa-mobile-alt" style="font-size: 1.5rem; color: #10b981; margin-bottom: 0.75rem; display: block;"></i>
                <div class="roy-step-title">Méthodes acceptées</div>
                <div class="roy-step-desc" style="margin-top: 0.3rem;"><strong>MTN Mobile Money</strong> et <strong>Orange Money</strong>. Renseignez votre numéro dans le formulaire de retrait.</div>
            </div>
            <div class="roy-card" style="border-top: 3px solid var(--blue-roi);">
                <i class="fas fa-clock" style="font-size: 1.5rem; color: var(--blue-roi); margin-bottom: 0.75rem; display: block;"></i>
                <div class="roy-step-title">Délai de traitement</div>
                <div class="roy-step-desc" style="margin-top: 0.3rem;">Votre demande est traitée par notre équipe sous <strong>24 à 48 heures ouvrables</strong>. Vous recevez un email de confirmation dès le virement effectué.</div>
            </div>
        </div>
    </section>

    <!-- CTA -->
    <div style="text-align: center; padding: 2.5rem; background: linear-gradient(135deg, rgba(255,103,0,0.08), rgba(0,62,105,0.08)); border-radius: 20px; border: 1px solid var(--border-color, #e2e8f0);">
        <h3 class="roy-step-title" style="font-size: 1.3rem; margin-bottom: 0.5rem;">Commencez à gagner dès aujourd'hui</h3>
        <p class="roy-body" style="margin-bottom: 1.5rem;">Publiez votre premier livre et rejoignez des auteurs qui monétisent leur passion.</p>
        <a href="{{ route('register') }}" class="btn btn-primary" style="margin-right: 0.75rem;">
            <i class="fas fa-pen-nib"></i> Devenir Auteur
        </a>
        <a href="{{ route('publishing-guide') }}" class="btn btn-outline">
            <i class="fas fa-book"></i> Guide de publication
        </a>
    </div>

</div>
</div>

@push('scripts')
<script>
function simulate() {
    const reads = parseFloat(document.getElementById('sim-reads').value) || 0;
    const price = parseFloat(document.getElementById('sim-price').value) || 0;
    const rate  = parseFloat(document.getElementById('sim-rate').value) || 0;
    const gain  = reads * price * (rate / 100);
    document.getElementById('sim-formula').textContent =
        reads + ' lectures × ' + price.toLocaleString('fr') + ' XAF × ' + rate + '%';
    document.getElementById('sim-value').textContent =
        gain.toLocaleString('fr', { maximumFractionDigits: 0 }) + ' XAF';
}
document.getElementById('sim-reads').addEventListener('input', simulate);
document.getElementById('sim-price').addEventListener('input', simulate);
document.getElementById('sim-rate').addEventListener('input', simulate);
simulate();
</script>
@endpush
@endsection
