<div align="center">

# 📚 OCaLi — Bibliothèque Numérique Africaine

**La plateforme qui transforme les mots en richesse**

[![Laravel](https://img.shields.io/badge/Laravel-11.x-FF2D20?style=flat-square&logo=laravel)](https://laravel.com)
[![PHP](https://img.shields.io/badge/PHP-8.2+-777BB4?style=flat-square&logo=php)](https://php.net)
[![License](https://img.shields.io/badge/Licence-Propriétaire-blue?style=flat-square)](LICENSE)
[![Status](https://img.shields.io/badge/Statut-Production--ready-10B981?style=flat-square)]()

</div>

---

## 📖 Présentation

**OCaLi** (Online Cameroon Library) est une bibliothèque numérique développée par [BLAKTEC](https://blaktec.cm) pour valoriser le patrimoine intellectuel et culturel camerounais et africain. La plateforme permet :

- Aux **lecteurs** d'accéder à des milliers d'œuvres numériques via des abonnements flexibles
- Aux **auteurs** de publier, monétiser et suivre les performances de leurs œuvres
- Aux **administrateurs** de gérer l'ensemble de la plateforme depuis un panneau dédié

---

## ✨ Fonctionnalités principales

### 👤 Authentification
- Inscription / Connexion classique (Lecteur, Auteur, Admin)
- Connexion sociale via **Google** et **Facebook** (Laravel Socialite)
- Vérification d'email et flux de complétion de profil

### 📚 Espace Lecteur
- Catalogue avec recherche avancée (filtre catégorie, type, langue, tri)
- Bibliothèque personnelle avec rétention de 50 % après expiration
- **Lecteur PDF.js** intégré avec protection anti-capture d'écran
- Signets de lecture avec navigation click-to-jump
- Système d'avis (note 1-5 étoiles + texte, achat vérifié)
- Progression sauvegardée automatiquement

### ✍️ Espace Auteur
- Publication de livres (PDF jusqu'à 100 Mo + couverture)
- Soumission → validation admin → publication
- Statistiques : vues, lectures, revenus par livre
- Chroniques / articles
- Wallet temps réel + demande de retrait Mobile Money

### 🛡️ Espace Administrateur
- Tableau de bord avec 7 KPIs
- Modération des publications (approbation / rejet motivé)
- Gestion des utilisateurs et des rôles
- Traitement des signalements (dismiss, warn, remove)
- Gestion des plans d'abonnement (CRUD)
- Envoi de newsletters
- Rapports financiers et statistiques
- Paramètres système configurables

### 🌐 Pages d'information
- `/help` — FAQ interactive avec recherche
- `/publishing-guide` — Guide complet de publication
- `/royalties` — Simulateur de gains auteurs
- `/contact` — Formulaire avec envoi email backend
- `/terms` — CGU (10 sections)
- `/privacy` — Politique de confidentialité (10 sections)

---

## 🛠️ Stack technique

| Composant | Technologie |
|-----------|-------------|
| Backend | Laravel 11 (PHP 8.2+) |
| Frontend | Blade + CSS vanilla (dark/light mode) |
| Base de données | MySQL 8 |
| Authentification | Laravel Auth + Socialite |
| Lecteur PDF | PDF.js |
| Paiements | Nokash / PayMooney (Mobile Money) |
| Emails | SMTP via `Mail::` Laravel |
| Multilinguisme | FR / EN (fichiers `lang/`) |

---

## 🚀 Installation locale

### Prérequis
- PHP >= 8.2
- Composer
- MySQL 8
- Node.js (optionnel, pour les assets)

### Étapes

```bash
# 1. Cloner le dépôt
git clone https://gitlab.com/ocali/web.git
cd web

# 2. Installer les dépendances PHP
composer install

# 3. Copier et configurer l'environnement
cp .env.example .env
php artisan key:generate

# 4. Configurer la base de données dans .env
# DB_DATABASE=ocali
# DB_USERNAME=root
# DB_PASSWORD=secret

# 5. Migrations + seeds (catégories, plans, admin)
php artisan migrate --seed

# 6. Lier le stockage public
php artisan storage:link

# 7. Lancer le serveur de développement
php artisan serve
```

L'application est accessible sur `http://localhost:8000`.

> **Compte admin par défaut** créé par le seeder — modifier les identifiants dans `database/seeders/AdminSeeder.php` avant le premier lancement.

---

## ⚙️ Configuration des services tiers

### Email (SMTP)
```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=ocali597198@gmail.com
MAIL_PASSWORD=your_app_password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=ocali597198@gmail.com
MAIL_FROM_NAME="OCaLi"
```

### Connexion sociale
```env
GOOGLE_CLIENT_ID=your_google_client_id
GOOGLE_CLIENT_SECRET=your_google_client_secret
GOOGLE_REDIRECT_URI=https://yourdomain.com/auth/google/callback

FACEBOOK_CLIENT_ID=your_facebook_app_id
FACEBOOK_CLIENT_SECRET=your_facebook_app_secret
FACEBOOK_REDIRECT_URI=https://yourdomain.com/auth/facebook/callback
```

### Paiements Mobile Money
```env
NOKASH_API_KEY=your_nokash_key
NOKASH_API_URL=https://api.nokash.net
PAYMOONEY_MERCHANT_ID=your_merchant_id
```

---

## 🗄️ Structure du projet

```
OCaLi-Web-Backend/
├── app/
│   ├── Http/Controllers/       # Contrôleurs web & API
│   ├── Models/                 # Modèles Eloquent
│   └── Services/               # EmailService, NokashService...
├── database/
│   ├── migrations/             # Schéma de base de données
│   └── seeders/                # Données initiales
├── docs/
│   ├── RAPPORT_AVANCEMENT_GLOBAL.md
│   └── RAPPORT_AVANCEMENT_GLOBAL.tex
├── resources/
│   ├── lang/fr/ & lang/en/    # Traductions
│   └── views/                 # Vues Blade
├── routes/
│   ├── web.php                 # Routes web
│   └── api.php                 # Routes API
└── storage/                    # Fichiers uploadés
```

---

## 📦 Déploiement en production

```bash
# Variables d'environnement
APP_ENV=production
APP_DEBUG=false

# Migrations + seeds (une seule fois)
php artisan migrate --seed --force

# Optimisations
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Permissions
chmod -R 775 storage bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache
```

---

## 📄 Licence

Ce projet est propriétaire. Tous droits réservés © 2026 **BLAKTEC**.  
Toute reproduction ou distribution sans autorisation écrite est interdite.

---

## 👨‍💻 Auteur

<div align="center">

**AZANGUE LEONEL DELMAT**  
Développeur Web Full-Stack

[![GitHub](https://img.shields.io/badge/GitHub-Delmat237-181717?style=flat-square&logo=github)](https://github.com/Delmat237)
[![Email](https://img.shields.io/badge/Email-azangueleonel9@gmail.com-EA4335?style=flat-square&logo=gmail)](mailto:azangueleonel9@gmail.com)
[![Phone](https://img.shields.io/badge/Tél-+237%20694%20773%20472-25D366?style=flat-square&logo=whatsapp)](tel:+237694773472)

*Développé pour **BLAKTEC** — Yaoundé, Cameroun 🇨🇲*

</div>
