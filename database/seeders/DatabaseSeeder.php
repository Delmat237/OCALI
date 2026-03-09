<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Setting;
use App\Models\SubscriptionPlan;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Create Admin User
        User::create([
            'name' => 'Admin OCaLi',
            'email' => 'admin@ocali.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'email_verified_at' => now(),
        ]);

        // Create Categories
        $categories = [
            ['name' => 'Romans', 'name_en' => 'Novels', 'icon' => 'fas fa-book', 'color' => '#FFA500'],
            ['name' => 'Science-Fiction', 'name_en' => 'Science Fiction', 'icon' => 'fas fa-rocket', 'color' => '#4169E1'],
            ['name' => 'Fantaisie', 'name_en' => 'Fantasy', 'icon' => 'fas fa-dragon', 'color' => '#9333EA'],
            ['name' => 'Thriller', 'name_en' => 'Thriller', 'icon' => 'fas fa-skull', 'color' => '#DC2626'],
            ['name' => 'Romance', 'name_en' => 'Romance', 'icon' => 'fas fa-heart', 'color' => '#EC4899'],
            ['name' => 'Histoire', 'name_en' => 'History', 'icon' => 'fas fa-landmark', 'color' => '#78716C'],
            ['name' => 'Biographie', 'name_en' => 'Biography', 'icon' => 'fas fa-user', 'color' => '#0891B2'],
            ['name' => 'Sciences', 'name_en' => 'Science', 'icon' => 'fas fa-atom', 'color' => '#16A34A'],
            ['name' => 'Philosophie', 'name_en' => 'Philosophy', 'icon' => 'fas fa-brain', 'color' => '#7C3AED'],
            ['name' => 'Développement Personnel', 'name_en' => 'Self-Help', 'icon' => 'fas fa-lightbulb', 'color' => '#F59E0B'],
            ['name' => 'Poésie', 'name_en' => 'Poetry', 'icon' => 'fas fa-feather', 'color' => '#8B5CF6'],
            ['name' => 'Jeunesse', 'name_en' => 'Children', 'icon' => 'fas fa-child', 'color' => '#06B6D4'],
            ['name' => 'Bande Dessinée', 'name_en' => 'Comics', 'icon' => 'fas fa-mask', 'color' => '#EAB308'],
            ['name' => 'Théâtre', 'name_en' => 'Theater', 'icon' => 'fas fa-theater-masks', 'color' => '#9F1239'],
            ['name' => 'Juridique', 'name_en' => 'Legal', 'icon' => 'fas fa-balance-scale', 'color' => '#1E3A8A'],
            ['name' => 'Essai', 'name_en' => 'Essay', 'icon' => 'fas fa-pen-fancy', 'color' => '#4D7C0F'],
        ];

        foreach ($categories as $index => $cat) {
            Category::create([
                'name' => $cat['name'],
                'name_en' => $cat['name_en'],
                'slug' => \Illuminate\Support\Str::slug($cat['name']),
                'icon' => $cat['icon'],
                'color' => $cat['color'],
                'order' => $index,
                'is_active' => true,
            ]);
        }

        // Create Subscription Plans
        // Reader Plans
        SubscriptionPlan::create([
            'name' => 'Lecteur Mensuel',
            'name_en' => 'Monthly Reader',
            'slug' => 'reader-monthly',
            'type' => 'reader',
            'duration' => 'monthly',
            'duration_days' => 30,
            'price' => 3000,
            'books_limit' => 6,
            'platform_commission_rate' => 0.20,
            'features' => ['6 livres par mois', 'Lecture illimitée', 'Accès mobile'],
            'order' => 1,
        ]);

        SubscriptionPlan::create([
            'name' => 'Lecteur Trimestriel',
            'name_en' => 'Quarterly Reader',
            'slug' => 'reader-quarterly',
            'type' => 'reader',
            'duration' => 'quarterly',
            'duration_days' => 90,
            'price' => 10000,
            'books_limit' => 20,
            'platform_commission_rate' => 0.20,
            'features' => ['20 livres par trimestre', 'Lecture illimitée', 'Accès mobile', 'Support prioritaire'],
            'is_popular' => true,
            'order' => 2,
        ]);

        SubscriptionPlan::create([
            'name' => 'Lecteur Annuel',
            'name_en' => 'Yearly Reader',
            'slug' => 'reader-yearly',
            'type' => 'reader',
            'duration' => 'yearly',
            'duration_days' => 365,
            'price' => 40000,
            'books_limit' => 80,
            'publications_limit' => 0,
            'platform_commission_rate' => 0.00,
            'features' => ['80 Books access', '80 livres par an', 'Lecture illimitée', 'Accès mobile', 'Support prioritaire', 'Contenus exclusifs'],
            'order' => 3,
        ]);

        SubscriptionPlan::create([
            'name' => 'Achat Unique',
            'name_en' => 'Single Purchase',
            'slug' => 'reader-single',
            'type' => 'reader',
            'duration' => 'yearly',
            'duration_days' => 36500,
            'price' => 600,
            'books_limit' => 1,
            'publications_limit' => 0,
            'platform_commission_rate' => 0.00,
            'features' => ['1 Book access', '1 livre', 'Lecture illimitée', 'Accès mobile'],
            'order' => 4,
        ]);

        // Author Plans
        SubscriptionPlan::create([
            'name' => 'Auteur Mensuel',
            'name_en' => 'Monthly Author',
            'slug' => 'author-monthly',
            'type' => 'author',
            'duration' => 'monthly',
            'duration_days' => 30,
            'price' => 0,
            'books_limit' => 0,
            'publications_limit' => 15,
            'platform_commission_rate' => 1.00,
            'features' => ['15 publications', 'Statistiques détaillées', 'Support auteur'],
            'order' => 4,
        ]);

        SubscriptionPlan::create([
            'name' => 'Auteur Trimestriel',
            'name_en' => 'Quarterly Author',
            'slug' => 'author-quarterly',
            'type' => 'author',
            'duration' => 'quarterly',
            'duration_days' => 90,
            'price' => 0,
            'books_limit' => 0,
            'publications_limit' => 50,
            'platform_commission_rate' => 1.00,
            'features' => ['50 publications', 'Statistiques détaillées', 'Support auteur', 'Mise en avant'],
            'is_popular' => true,
            'order' => 5,
        ]);

        SubscriptionPlan::create([
            'name' => 'Auteur Annuel',
            'name_en' => 'Yearly Author',
            'slug' => 'author-yearly',
            'type' => 'author',
            'duration' => 'yearly',
            'duration_days' => 365,
            'price' => 0,
            'books_limit' => 0,
            'publications_limit' => 200,
            'platform_commission_rate' => 1.00,
            'features' => ['200 publications', 'Statistiques détaillées', 'Support VIP', 'Mise en avant premium', 'Outils marketing'],
            'order' => 6,
        ]);

        // Initialize Settings
        Setting::initializeDefaults();

        // Additional settings
        Setting::setValue('author_share_per_subscription', 500, 'integer', 'payments');
        Setting::setValue('min_withdrawal_threshold', 5000, 'integer', 'payments');
        Setting::setValue('min_reading_threshold', 5, 'integer', 'reading');
    }
}
