<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        // Only seed if no categories exist
        if (Category::count() > 0) {
            $this->command->info('Categories already exist. Skipping seed.');
            return;
        }

        $categories = [
            ['name' => 'Romans',                  'name_en' => 'Novels',          'icon' => 'fas fa-book',        'color' => '#FFA500'],
            ['name' => 'Science-Fiction',          'name_en' => 'Science Fiction', 'icon' => 'fas fa-rocket',      'color' => '#4169E1'],
            ['name' => 'Fantaisie',                'name_en' => 'Fantasy',         'icon' => 'fas fa-dragon',      'color' => '#9333EA'],
            ['name' => 'Thriller',                 'name_en' => 'Thriller',        'icon' => 'fas fa-skull',       'color' => '#DC2626'],
            ['name' => 'Romance',                  'name_en' => 'Romance',         'icon' => 'fas fa-heart',       'color' => '#EC4899'],
            ['name' => 'Histoire',                 'name_en' => 'History',         'icon' => 'fas fa-landmark',    'color' => '#78716C'],
            ['name' => 'Biographie',               'name_en' => 'Biography',       'icon' => 'fas fa-user',        'color' => '#0891B2'],
            ['name' => 'Sciences',                 'name_en' => 'Science',         'icon' => 'fas fa-atom',        'color' => '#16A34A'],
            ['name' => 'Philosophie',              'name_en' => 'Philosophy',      'icon' => 'fas fa-brain',       'color' => '#7C3AED'],
            ['name' => 'Développement Personnel',  'name_en' => 'Self-Help',       'icon' => 'fas fa-lightbulb',   'color' => '#F59E0B'],
            ['name' => 'Poésie',                   'name_en' => 'Poetry',          'icon' => 'fas fa-feather',     'color' => '#8B5CF6'],
            ['name' => 'Jeunesse',                 'name_en' => 'Children',        'icon' => 'fas fa-child',       'color' => '#06B6D4'],
        ];

        foreach ($categories as $index => $cat) {
            Category::create([
                'name'     => $cat['name'],
                'name_en'  => $cat['name_en'],
                'slug'     => Str::slug($cat['name']),
                'icon'     => $cat['icon'],
                'color'    => $cat['color'],
                'order'    => $index + 1,
                'is_active' => true,
            ]);
        }

        $this->command->info('✅ Seeded ' . count($categories) . ' categories.');
    }
}
