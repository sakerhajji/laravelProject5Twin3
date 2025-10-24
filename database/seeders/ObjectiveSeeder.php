<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Objective;

class ObjectiveSeeder extends Seeder
{
    public function run(): void
    {
        $items = [
            [
                'title' => 'Perdre du poids',
                'description' => 'Atteindre un poids santé avec un suivi régulier.',
                'unit' => 'kg',
                'target_value' => 5,
                'category' => 'sante',
                'cover_url' => 'images/objectives/perdrepoids.png',
            ],
            [
                'title' => 'Courir chaque semaine',
                'description' => 'Total de kilomètres à courir par semaine.',
                'unit' => 'km',
                'target_value' => 20,
                'category' => 'sport',
                'cover_url' => 'images/objectives/courir.png',
            ],
            [
                'title' => 'Améliorer le sommeil',
                'description' => 'Nombre d\'heures de sommeil par nuit.',
                'unit' => 'h',
                'target_value' => 8,
                'category' => 'sommeil',
                'cover_url' => 'images/objectives/Améliorer le sommeil.png',
            ],
            [
                'title' => 'Hydratation quotidienne',
                'description' => 'Quantité d\'eau à boire par jour.',
                'unit' => 'L',
                'target_value' => 2,
                'category' => 'nutrition',
                'cover_url' => 'images/objectives/Hydratation quotidienne.png',
            ],
            [
                'title' => 'Calories brûlées par jour',
                'description' => 'Objectif de calories brûlées quotidiennement.',
                'unit' => 'kcal',
                'target_value' => 500,
                'category' => 'sport',
                'cover_url' => 'images/objectives/Calories brûlées par jour.png',
            ],
            [
                'title' => 'Manger équilibré',
                'description' => 'Adopter une alimentation variée et saine.',
                'unit' => 'repas',
                'target_value' => 21,
                'category' => 'nutrition',
                'cover_url' => 'images/objectives/5-fruits.png',
            ],
            [
                'title' => 'Hydratation quotidienne',
                'description' => 'Quantité d\'eau à boire par jour.',
                'unit' => 'L',
                'target_value' => 2,
                'category' => 'nutrition',
                'cover_url' => 'images/objectives/Hydratation quotidienne.png',
            ],
            [
                'title' => 'Masse musculaire',
                'description' => 'Objectif de masse musculaire.',
                'unit' => 'kg',
                'target_value' => 60,
                'category' => 'sport',
                'cover_url' => 'images/objectives/masse.png',
            ],
            [
                'title' => 'Prendre du stress',
                'description' => 'Gérer le stress efficacement.',
                'unit' => 'niveau',
                'target_value' => 3,
                'category' => 'sante',
                'cover_url' => 'images/objectives/stress.png',
            ],
            [
                'title' => '10000 pas par jour',
                'description' => 'Atteindre 10 000 pas quotidiens.',
                'unit' => 'pas',
                'target_value' => 10000,
                'category' => 'sport',
                'cover_url' => 'images/objectives/10000pas.png',
            ],
        ];

        foreach ($items as $data) {
            Objective::updateOrCreate(
                [
                    'title' => $data['title'],
                    'unit' => $data['unit'],
                    'category' => $data['category'],
                ],
                $data
            );
        }
    }
}