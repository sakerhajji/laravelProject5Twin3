<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
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
        // Récupérer les catégories et utilisateurs existants
        $categories = Category::all();
        $users = User::where('role', 'admin')->get();
        
        if ($categories->isEmpty() || $users->isEmpty()) {
            $this->command->warn('Assurez-vous que les catégories et utilisateurs admin existent avant de lancer ce seeder.');
            return;
        }

        // Rechercher les catégories par nom (plus fiable que par ID)
        $cardioCategory = Category::where('title', 'like', '%Cardio%')->orWhere('title', 'like', '%Sport%')->first();
        $musculationCategory = Category::where('title', 'like', '%Musculation%')->orWhere('title', 'like', '%Fitness%')->first();
        $yogaCategory = Category::where('title', 'like', '%Yoga%')->orWhere('title', 'like', '%Relaxation%')->first();
        
        // Si les catégories spécifiques n'existent pas, utiliser les premières disponibles
        if (!$cardioCategory) $cardioCategory = $categories->first();
        if (!$musculationCategory) $musculationCategory = $categories->skip(1)->first() ?? $categories->first();
        if (!$yogaCategory) $yogaCategory = $categories->skip(2)->first() ?? $categories->first();

        $activities = [
            // Cardio
            [
                'title' => 'Course à pied débutant',
                'description' => 'Programme de course pour débutants - 20 minutes d\'alternance marche/course',
                'image' => 'course-debutant.jpg',
                'time' => 20,
                'category_id' => $cardioCategory->id,
                'likes_count' => 45,
                'saves_count' => 23
            ],
            [
                'title' => 'Vélo d\'appartement intensif',
                'description' => 'Séance de vélo d\'appartement avec variations d\'intensité - 30 minutes',
                'image' => 'velo-intensif.jpg',
                'time' => 30,
                'category_id' => $cardioCategory->id,
                'likes_count' => 32,
                'saves_count' => 18
            ],
            
            // Musculation
            [
                'title' => 'Développé couché',
                'description' => 'Exercice de développé couché pour pectoraux - 4 séries de 10 répétitions',
                'image' => 'developpe-couche.jpg',
                'time' => 25,
                'category_id' => $musculationCategory->id,
                'likes_count' => 67,
                'saves_count' => 34
            ],
            [
                'title' => 'Squats avec poids',
                'description' => 'Squats avec haltères pour cuisses et fessiers - 3 séries de 12',
                'image' => 'squats-poids.jpg',
                'time' => 20,
                'category_id' => $musculationCategory->id,
                'likes_count' => 54,
                'saves_count' => 29
            ],
            
            // Yoga
            [
                'title' => 'Salutation au soleil',
                'description' => 'Enchaînement classique de yoga pour débuter la journée',
                'image' => 'salutation-soleil.jpg',
                'time' => 15,
                'category_id' => $yogaCategory->id,
                'likes_count' => 89,
                'saves_count' => 56
            ],
            [
                'title' => 'Yoga du soir détente',
                'description' => 'Séance de yoga relaxante pour se détendre en fin de journée',
                'image' => 'yoga-soir.jpg',
                'time' => 25,
                'category_id' => $yogaCategory->id,
                'likes_count' => 72,
                'saves_count' => 41
            ],
            
            // Pilates
            [
                'title' => 'Pilates core débutant',
                'description' => 'Exercices Pilates pour renforcer les muscles profonds du tronc',
                'image' => 'pilates-core.jpg',
                'time' => 20,
                'category_id' => $yogaCategory->id, // Utiliser yoga category comme fallback
                'likes_count' => 38,
                'saves_count' => 22
            ],
            
            // Fitness
            [
                'title' => 'Circuit training complet',
                'description' => 'Circuit de fitness combinant cardio et renforcement musculaire',
                'image' => 'circuit-training.jpg',
                'time' => 35,
                'category_id' => $musculationCategory->id,
                'likes_count' => 61,
                'saves_count' => 35
            ],
            
            // Étirements
            [
                'title' => 'Étirements post-workout',
                'description' => 'Séance d\'étirements pour récupérer après l\'entraînement',
                'image' => 'etirements-post.jpg',
                'time' => 10,
                'category_id' => $yogaCategory->id,
                'likes_count' => 43,
                'saves_count' => 28
            ],
            
            // HIIT
            [
                'title' => 'HIIT Tabata 4 minutes',
                'description' => 'Entraînement Tabata ultra-intensif de 4 minutes',
                'image' => 'hiit-tabata.jpg',
                'time' => 4,
                'category_id' => $cardioCategory->id,
                'likes_count' => 95,
                'saves_count' => 67
            ],
            [
                'title' => 'HIIT poids du corps',
                'description' => 'Circuit HIIT utilisant uniquement le poids du corps - 20 minutes',
                'image' => 'hiit-bodyweight.jpg',
                'time' => 20,
                'category_id' => $cardioCategory->id,
                'likes_count' => 78,
                'saves_count' => 45
            ],
            
            // Danse
            [
                'title' => 'Zumba débutant',
                'description' => 'Cours de Zumba énergique pour débutants',
                'image' => 'zumba-debutant.jpg',
                'time' => 45,
                'category_id' => $cardioCategory->id,
                'likes_count' => 56,
                'saves_count' => 32
            ],
            
            // Méditation
            [
                'title' => 'Méditation guidée 10min',
                'description' => 'Méditation guidée pour débutants - focus sur la respiration',
                'image' => 'meditation-guidee.jpg',
                'time' => 10,
                'category_id' => $yogaCategory->id,
                'likes_count' => 84,
                'saves_count' => 52
            ],
            
            // Aqua Fitness
            [
                'title' => 'Aqua jogging',
                'description' => 'Course dans l\'eau pour un cardio doux sur les articulations',
                'image' => 'aqua-jogging.jpg',
                'time' => 30,
                'category_id' => $cardioCategory->id,
                'likes_count' => 29,
                'saves_count' => 15
            ]
        ];

        foreach ($activities as $activity) {
            // Assigner un utilisateur admin aléatoire
            $activity['user_id'] = $users->random()->id;
            Activity::create($activity);
        }

        $this->command->info('✅ ' . count($activities) . ' activités créées avec succès !');
    }
}
