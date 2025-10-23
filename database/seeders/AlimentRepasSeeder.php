<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Aliment;
use App\Models\Repas;
use App\Models\User;

class AlimentRepasSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Get the first user to associate meals with
        $user = User::first();

        if (!$user) {
            $this->command->info('No user found. Please seed users first.');
            return;
        }

        // Create aliments
        $aliments = [
            ['nom' => 'Poulet grillé', 'calories' => 165, 'proteines' => 31, 'glucides' => 0, 'lipides' => 3.6],
            ['nom' => 'Riz blanc', 'calories' => 130, 'proteines' => 2.7, 'glucides' => 28, 'lipides' => 0.3],
            ['nom' => 'Brocoli', 'calories' => 55, 'proteines' => 3.7, 'glucides' => 11, 'lipides' => 0.6],
            ['nom' => 'Oeuf', 'calories' => 155, 'proteines' => 13, 'glucides' => 1.1, 'lipides' => 11],
            ['nom' => 'Banane', 'calories' => 89, 'proteines' => 1.1, 'glucides' => 23, 'lipides' => 0.3],
        ];

        foreach ($aliments as $alimentData) {
            Aliment::create($alimentData);
        }

        $poulet = Aliment::where('nom', 'Poulet grillé')->first();
        $riz = Aliment::where('nom', 'Riz blanc')->first();
        $brocoli = Aliment::where('nom', 'Brocoli')->first();
        $oeuf = Aliment::where('nom', 'Oeuf')->first();
        $banane = Aliment::where('nom', 'Banane')->first();

        // Create Repas
        $petitDejeuner = Repas::create([
            'nom' => 'Petit-déjeuner équilibré',
            'description' => 'Un petit-déjeuner pour bien commencer la journée.',
            'user_id' => $user->id,
        ]);

        $dejeuner = Repas::create([
            'nom' => 'Déjeuner protéiné',
            'description' => 'Un repas de midi riche en protéines.',
            'user_id' => $user->id,
        ]);

        $diner = Repas::create([
            'nom' => 'Dîner léger',
            'description' => 'Un dîner léger et nutritif.',
            'user_id' => $user->id,
        ]);

        // Attach aliments to repas
        $petitDejeuner->aliments()->attach([
            $banane->id => ['quantite' => 100], // 100g (1 banane moyenne)
            $oeuf->id => ['quantite' => 100], // 100g (2 oeufs)
        ]);

        $dejeuner->aliments()->attach([
            $poulet->id => ['quantite' => 150], // 150g
            $riz->id => ['quantite' => 200], // 200g
            $brocoli->id => ['quantite' => 150], // 150g
        ]);

        $diner->aliments()->attach([
           $poulet->id => ['quantite' => 150], // 150g
            $riz->id => ['quantite' => 200], // 200g
            $brocoli->id => ['quantite' => 150], // 150g
        ]);
    }
}
