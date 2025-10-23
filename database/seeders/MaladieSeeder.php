<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Maladie;
use Carbon\Carbon;

class MaladieSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $maladies = [
            [
                'nom' => 'Diabète Type 2',
                'description' => 'Maladie chronique caractérisée par une hyperglycémie due à une résistance à l\'insuline et/ou un déficit de sécrétion d\'insuline.',
                'traitement' => 'Régime alimentaire équilibré, exercice physique régulier, médicaments antidiabétiques oraux, insuline si nécessaire. Surveillance régulière de la glycémie.',
                'prevention' => 'Maintenir un poids santé, adopter une alimentation équilibrée riche en fibres, pratiquer une activité physique régulière, éviter le tabac et limiter l\'alcool.',
                'status' => Maladie::STATUS_ACTIVE,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'nom' => 'Hypertension Artérielle',
                'description' => 'Élévation chronique de la pression artérielle pouvant entraîner des complications cardiovasculaires.',
                'traitement' => 'Médicaments antihypertenseurs (IEC, ARA2, diurétiques, bêta-bloquants), modification du mode de vie, surveillance régulière.',
                'prevention' => 'Réduire la consommation de sel, maintenir un poids santé, exercice régulier, limiter l\'alcool, gérer le stress, éviter le tabac.',
                'status' => Maladie::STATUS_ACTIVE,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'nom' => 'Asthme',
                'description' => 'Maladie inflammatoire chronique des voies respiratoires caractérisée par des épisodes de dyspnée, de sifflements et de toux.',
                'traitement' => 'Bronchodilatateurs, corticoïdes inhalés, éviction des allergènes, plan d\'action personnalisé, éducation thérapeutique.',
                'prevention' => 'Éviter les déclencheurs (allergènes, irritants), maintenir un environnement propre, vaccination antigrippale, arrêt du tabac.',
                'status' => Maladie::STATUS_ACTIVE,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'nom' => 'Arthrite Rhumatoïde',
                'description' => 'Maladie auto-immune chronique affectant principalement les articulations, causant inflammation, douleur et destruction articulaire.',
                'traitement' => 'Anti-inflammatoires, méthotrexate, biologiques, corticoïdes, kinésithérapie, ergothérapie.',
                'prevention' => 'Diagnostic précoce, arrêt du tabac, exercice adapté, alimentation anti-inflammatoire, gestion du stress.',
                'status' => Maladie::STATUS_ACTIVE,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'nom' => 'Dépression Majeure',
                'description' => 'Trouble de l\'humeur caractérisé par une tristesse persistante, une perte d\'intérêt et des troubles du fonctionnement.',
                'traitement' => 'Antidépresseurs, psychothérapie (TCC, psychodynamique), thérapies complémentaires, hospitalisation si nécessaire.',
                'prevention' => 'Maintenir des liens sociaux, exercice régulier, gestion du stress, sommeil suffisant, éviter alcool et drogues.',
                'status' => Maladie::STATUS_ACTIVE,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'nom' => 'Migraine',
                'description' => 'Céphalée primaire récurrente, souvent unilatérale, pulsatile, accompagnée de nausées et de photophobie.',
                'traitement' => 'Antalgiques, triptans, traitement de fond si nécessaire, éviction des facteurs déclenchants.',
                'prevention' => 'Identifier et éviter les déclencheurs, régularité des repas et du sommeil, gestion du stress, hydratation.',
                'status' => Maladie::STATUS_ACTIVE,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'nom' => 'Hypothyroïdie',
                'description' => 'Déficit en hormones thyroïdiennes entraînant un ralentissement du métabolisme.',
                'traitement' => 'Lévothyroxine (hormone thyroïdienne de substitution), surveillance biologique régulière, adaptation des doses.',
                'prevention' => 'Apport suffisant en iode, éviter les goitrogènes, surveillance en cas d\'antécédents familiaux.',
                'status' => Maladie::STATUS_ACTIVE,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'nom' => 'Gastrite Chronique',
                'description' => 'Inflammation chronique de la muqueuse gastrique pouvant évoluer vers l\'ulcère ou le cancer.',
                'traitement' => 'Éradication d\'Helicobacter pylori si présent, inhibiteurs de la pompe à protons, modification alimentaire.',
                'prevention' => 'Éviter les irritants (AINS, alcool, tabac), alimentation équilibrée, gestion du stress.',
                'status' => Maladie::STATUS_ACTIVE,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'nom' => 'Ostéoporose',
                'description' => 'Diminution de la densité osseuse augmentant le risque de fractures.',
                'traitement' => 'Bisphosphonates, supplémentation en calcium et vitamine D, exercice physique, prévention des chutes.',
                'prevention' => 'Apport suffisant en calcium et vitamine D, exercice de résistance, éviter tabac et alcool excessif.',
                'status' => Maladie::STATUS_ACTIVE,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'nom' => 'Insuffisance Cardiaque',
                'description' => 'Incapacité du cœur à pomper efficacement le sang pour répondre aux besoins de l\'organisme.',
                'traitement' => 'IEC/ARA2, bêta-bloquants, diurétiques, restriction sodée, surveillance du poids, rééducation cardiaque.',
                'prevention' => 'Contrôle des facteurs de risque cardiovasculaire, traitement de l\'hypertension et du diabète.',
                'status' => Maladie::STATUS_ACTIVE,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]
        ];

        foreach ($maladies as $maladie) {
            Maladie::create($maladie);
        }

        $this->command->info('Maladies créées avec succès!');
    }
}
