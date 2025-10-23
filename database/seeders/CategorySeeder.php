<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            [
                'title' => 'Cardio',
                'description' => 'Exercices cardiovasculaires pour améliorer l\'endurance et brûler les calories',
                'image' => 'cardio.jpg'
            ],
            [
                'title' => 'Musculation',
                'description' => 'Exercices de renforcement musculaire et développement de la force',
                'image' => 'musculation.jpg'
            ],
            [
                'title' => 'Yoga',
                'description' => 'Pratiques de yoga pour la flexibilité, l\'équilibre et la relaxation',
                'image' => 'yoga.jpg'
            ],
            [
                'title' => 'Pilates',
                'description' => 'Exercices de Pilates pour le renforcement du core et la posture',
                'image' => 'pilates.jpg'
            ],
            [
                'title' => 'Fitness',
                'description' => 'Exercices de fitness général pour une remise en forme complète',
                'image' => 'fitness.jpg'
            ],
            [
                'title' => 'Étirements',
                'description' => 'Exercices d\'étirement pour améliorer la flexibilité et récupération',
                'image' => 'etirements.jpg'
            ],
            [
                'title' => 'HIIT',
                'description' => 'Entraînement par intervalles à haute intensité pour brûler rapidement',
                'image' => 'hiit.jpg'
            ],
            [
                'title' => 'Danse',
                'description' => 'Activités de danse pour le cardio et le plaisir de bouger',
                'image' => 'danse.jpg'
            ],
            [
                'title' => 'Méditation',
                'description' => 'Pratiques de méditation pour la relaxation et le bien-être mental',
                'image' => 'meditation.jpg'
            ],
            [
                'title' => 'Aqua Fitness',
                'description' => 'Exercices aquatiques pour un entraînement doux et efficace',
                'image' => 'aqua-fitness.jpg'
            ]
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }
    }
}
