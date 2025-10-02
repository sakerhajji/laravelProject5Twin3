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
                'cover_url' => 'https://images.unsplash.com/photo-1514996937319-344454492b37?q=80&w=1200&auto=format&fit=crop',
            ],
            [
                'title' => 'Courir chaque semaine',
                'description' => 'Total de kilomètres à courir par semaine.',
                'unit' => 'km',
                'target_value' => 20,
                'category' => 'activite',
                'cover_url' => 'https://images.unsplash.com/photo-1540573133985-87b6da6d54a9?q=80&w=1200&auto=format&fit=crop',
            ],
            [
                'title' => 'Améliorer le sommeil',
                'description' => 'Nombre d\'heures de sommeil par nuit.',
                'unit' => 'h',
                'target_value' => 8,
                'category' => 'sommeil',
                'cover_url' => 'https://images.unsplash.com/photo-1519710164239-da123dc03ef4?q=80&w=1200&auto=format&fit=crop',
            ],
            [
                'title' => 'Hydratation quotidienne',
                'description' => 'Quantité d\'eau à boire par jour.',
                'unit' => 'L',
                'target_value' => 2,
                'category' => 'nutrition',
                'cover_url' => 'https://images.unsplash.com/photo-1517094051258-2b1f1d3b7b8e?q=80&w=1200&auto=format&fit=crop',
            ],
            [
                'title' => 'Calories brûlées par jour',
                'description' => 'Objectif de calories brûlées quotidiennement.',
                'unit' => 'kcal',
                'target_value' => 500,
                'category' => 'activite',
                'cover_url' => 'https://images.unsplash.com/photo-1548690312-e3b507d8c110?q=80&w=1200&auto=format&fit=crop',
            ],
        ];

        foreach ($items as $data) {
            Objective::firstOrCreate(
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


