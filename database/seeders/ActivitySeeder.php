<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Activity;
use App\Models\Category;
use App\Models\User;

class ActivitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = Category::all();
        $users = User::where('role', 'admin')->get();

        if ($categories->isEmpty() || $users->isEmpty()) {
            $this->command->warn('Assurez-vous que les catégories et utilisateurs admin existent avant de lancer ce seeder.');
            return;
        }

        $cardioCategory = Category::where('title', 'like', '%Cardio%')->first() ?? $categories->first();
        $musculationCategory = Category::where('title', 'like', '%Musculation%')->first() ?? $categories->skip(1)->first() ?? $categories->first();
        $yogaCategory = Category::where('title', 'like', '%Yoga%')->first() ?? $categories->skip(2)->first() ?? $categories->first();

        $activities = [
            // Cardio
            [
                'title' => 'Course à pied débutant',
                'description' => 'Programme de course pour débutants - 20 minutes d\'alternance marche/course',
                'media_url' => 'course-debutant.jpg',
                'media_type' => 'image',
                'time' => 20,
                'category_id' => $cardioCategory->id,
                'likes_count' => 45,
                'saves_count' => 23
            ],
            [
                'title' => 'Vélo d\'appartement intensif',
                'description' => 'Séance de vélo d\'appartement avec variations d\'intensité - 30 minutes',
                'media_url' => 'velo-intensif.jpg',
                'media_type' => 'image',
                'time' => 30,
                'category_id' => $cardioCategory->id,
                'likes_count' => 32,
                'saves_count' => 18
            ],

            // Musculation
            [
                'title' => 'Développé couché',
                'description' => 'Exercice de développé couché pour pectoraux - 4 séries de 10 répétitions',
                'media_url' => 'developpe-couche.jpg',
                'media_type' => 'image',
                'time' => 25,
                'category_id' => $musculationCategory->id,
                'likes_count' => 67,
                'saves_count' => 34
            ],

            [
                'title' => 'Squats avec poids',
                'description' => 'Squats avec haltères pour cuisses et fessiers - 3 séries de 12',
                'media_url' => 'squats-poids.jpg',
                'media_type' => 'image',
                'time' => 20,
                'category_id' => $musculationCategory->id,
                'likes_count' => 54,
                'saves_count' => 29
            ],

            // Yoga
            [
                'title' => 'Salutation au soleil',
                'description' => 'Enchaînement classique de yoga pour débuter la journée',
                'media_url' => 'salutation-soleil.jpg',
                'media_type' => 'image',
                'time' => 15,
                'category_id' => $yogaCategory->id,
                'likes_count' => 89,
                'saves_count' => 56
            ],
            [
                'title' => 'Yoga du soir détente',
                'description' => 'Séance de yoga relaxante pour se détendre en fin de journée',
                'media_url' => 'yoga-soir.jpg',
                'media_type' => 'image',
                'time' => 25,
                'category_id' => $yogaCategory->id,
                'likes_count' => 72,
                'saves_count' => 41
            ],

            // Pilates
            [
                'title' => 'Pilates core débutant',
                'description' => 'Exercices Pilates pour renforcer les muscles profonds du tronc',
                'media_url' => 'pilates-core.jpg',
                'media_type' => 'image',
                'time' => 20,
                'category_id' => $yogaCategory->id,
                'likes_count' => 38,
                'saves_count' => 22
            ],

            // Fitness
            [
                'title' => 'Circuit training complet',
                'description' => 'Circuit de fitness combinant cardio et renforcement musculaire',
                'media_url' => 'circuit-training.jpg',
                'media_type' => 'image',
                'time' => 35,
                'category_id' => $musculationCategory->id,
                'likes_count' => 61,
                'saves_count' => 35
            ],

            // Étirements
            [
                'title' => 'Étirements post-workout',
                'description' => 'Séance d\'étirements pour récupérer après l\'entraînement',
                'media_url' => 'etirements-post.jpg',
                'media_type' => 'image',
                'time' => 10,
                'category_id' => $yogaCategory->id,
                'likes_count' => 43,
                'saves_count' => 28
            ],

            // HIIT
            [
                'title' => 'HIIT Tabata 4 minutes',
                'description' => 'Entraînement Tabata ultra-intensif de 4 minutes',
                'media_url' => 'hiit-tabata.jpg',
                'media_type' => 'image',
                'time' => 4,
                'category_id' => $cardioCategory->id,
                'likes_count' => 95,
                'saves_count' => 67
            ],
            [
                'title' => 'HIIT poids du corps',
                'description' => 'Circuit HIIT utilisant uniquement le poids du corps - 20 minutes',
                'media_url' => 'hiit-bodyweight.jpg',
                'media_type' => 'image',
                'time' => 20,
                'category_id' => $cardioCategory->id,
                'likes_count' => 78,
                'saves_count' => 45
            ],

            // Danse
            [
                'title' => 'Zumba débutant',
                'description' => 'Cours de Zumba énergique pour débutants',
                'media_url' => 'zumba-debutant.jpg',
                'media_type' => 'image',
                'time' => 45,
                'category_id' => $cardioCategory->id,
                'likes_count' => 56,
                'saves_count' => 32
            ],

            // Méditation
            [
                'title' => 'Méditation guidée 10min',
                'description' => 'Méditation guidée pour débutants - focus sur la respiration',
                'media_url' => 'meditation-guidee.jpg',
                'media_type' => 'image',
                'time' => 10,
                'category_id' => $yogaCategory->id,
                'likes_count' => 84,
                'saves_count' => 52
            ],

            // Aqua Fitness
            [
                'title' => 'Aqua jogging',
                'description' => 'Course dans l\'eau pour un cardio doux sur les articulations',
                'media_url' => 'aqua-jogging.jpg',
                'media_type' => 'image',
                'time' => 30,
                'category_id' => $cardioCategory->id,
                'likes_count' => 29,
                'saves_count' => 15
            ],
        ];

        foreach ($activities as $activity) {
            $activity['user_id'] = $users->random()->id;
            Activity::create($activity);
        }

        $this->command->info('✅ ' . count($activities) . ' activités créées avec succès !');
    }
}
