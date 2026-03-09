# Rapport d'Avancement Global — OCaLi Web

**Dernière mise à jour : Mars 2026** — Application fonctionnelle, déployable.

---

## 1. Fonctionnalités terminées et opérationnelles ✅

### Architecture & Socle technique
- Framework Laravel (backend monolithique)
- Authentification classique + Socialite (Google, Facebook)
- Système de thème Dark/Light avec persistance de session
- Multilinguisme FR/EN complet
- Header responsive avec menu hamburger mobile
- Seeds automatiques : 16 catégories, 7 plans, admin initial, paramètres

### Espace Lecteur
- Catalogue, exploration et recherche avancée
- Bibliothèque personnelle (ajout, suppression, rétention 50 % post-expiration)
- Lecteur PDF.js intégré (anti-capture d'écran : filigrane, blocage touches, détection de focus)
- Progression de lecture sauvegardée automatiquement
- Signets de lecture : ajout + click-to-jump vers la page sauvegardée
- Système d'avis : note 1-5 étoiles + texte, vérification doublon, `is_verified_purchase`

### Espace Auteur
- Publication de livres (CRUD complet, upload PDF 100 Mo + couverture, ISBN/DOI)
- Statistiques auteur : vues, lectures, revenus par livre
- Chroniques/articles (CRUD complet)
- Wallet : solde en temps réel, 10 dernières transactions
- Demande de retrait MTN MoMo / Orange Money (avec seuil minimum et seuil de lectures)

### Espace Administrateur
- Tableau de bord : 7 KPIs
- Gestion utilisateurs (CRUD, activation/désactivation, rôles)
- Validation des publications (approbation → email newsletter, rejet motivé)
- Signalements : dismiss, warn_author, remove_book
- Plans d'abonnement (CRUD complet)
- Newsletter (envoi campagne à tous les abonnés actifs)
- Statistiques : top 10 livres & auteurs, évolution 12 mois
- Rapport financier : revenus, commissions, retraits par mois
- Livre de bienvenue (désignation d'un livre gratuit pour les nouveaux inscrits)
- Paramètres système (panneau de configuration global)

### Pages d'information
- `/contact` — Formulaire fonctionnel → email envoyé à l'admin (Mail + replyTo)
- `/help` — FAQ complète avec 4 sections, barre de recherche, accordéons, dark mode
- `/publishing-guide` — Guide complet (5 étapes, standards qualité, processus révision)
- `/royalties` — Gains auteurs (4 étapes, tableau des taux, simulateur interactif)
- `/terms` — CGU : 10 sections rédigées
- `/privacy` — Politique de confidentialité : 10 sections rédigées

---

## 2. Pages à relire et valider par BLAKTEC ⚠️

Ces pages contiennent du contenu rédigé par l'équipe technique à partir des informations disponibles. **Le contenu doit être revu, validé et complété par BLAKTEC avant la mise en ligne officielle.**

| Page | Fichier | Points à vérifier |
|------|---------|-------------------|
| **Royalties** | `resources/views/pages/royalties.blade.php` | Vérifier les taux exacts (50%, 65%, 70%) — sont-ils conformes aux plans réels ? Vérifier le seuil de retrait minimum affiché. |
| **Guide de publication** | `resources/views/pages/publishing-guide.blade.php` | Vérifier la taille max fichier (100 Mo ?), délai de révision (24-48h ?), types de contenus acceptés. |
| **Politique de confidentialité** | `resources/views/pages/privacy.blade.php` | Faire valider par un juriste / DPO. Vérifier la durée de conservation, les sous-traitants et la mention CPDP Cameroun si applicable. |
| **CGU** | `resources/views/pages/terms.blade.php` | Faire valider par un juriste. Vérifier la juridiction compétente, les clauses de remboursement et la politique de résiliation. |
| **Centre d'aide** | `resources/views/pages/help.blade.php` | Vérifier toutes les réponses FAQ (délais, seuils, politique téléchargement, etc.) selon les décisions finales de BLAKTEC. |
| **Contact** | `resources/views/pages/contact.blade.php` | Vérifier le numéro de téléphone affiché (+237 659461197) et l'adresse (Yaoundé, Cameroun). |

> **⚠️ Note importante :** l'email de contact est actuellement `ocali597198@gmail.com`. Pour la mise en production, il est fortement recommandé de configurer un email professionnel (ex. `contact@ocali.com`) via un service SMTP dédié (Mailgun, SendGrid, etc.) et de mettre à jour la variable `MAIL_FROM_ADDRESS` dans `.env`.

---

## 3. Tâches de déploiement 🚀

```bash
# 1. Variables d'environnement (.env)
APP_ENV=production
APP_DEBUG=false
MAIL_MAILER=smtp
MAIL_FROM_ADDRESS=ocali597198@gmail.com

# 2. Migrations + seeds (à exécuter UNE FOIS sur le serveur)
php artisan migrate --seed --force

# 3. Optimisations production
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

> Les seeds créent automatiquement : 16 catégories, 7 plans d'abonnement, le premier compte admin et les paramètres système. **Ne pas exécuter `db:seed` plusieurs fois** sans `migrate:fresh` pour éviter les doublons.
