<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Asymptome;
use Carbon\Carbon;

class AsymptomeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $asymptomes = [
            // Asymptômes généraux
            [
                'nom' => 'Fièvre',
                'description' => 'Élévation de la température corporelle au-dessus de 38°C, souvent signe d\'infection ou d\'inflammation.',
                'gravite' => 'modérée',
                'status' => Asymptome::STATUS_ACTIVE,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'nom' => 'Fatigue',
                'description' => 'Sensation d\'épuisement, de manque d\'énergie persistant, affectant les activités quotidiennes.',
                'gravite' => 'légère',
                'status' => Asymptome::STATUS_ACTIVE,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'nom' => 'Douleur thoracique',
                'description' => 'Douleur localisée dans la région du thorax, pouvant être cardiaque, pulmonaire ou musculaire.',
                'gravite' => 'sévère',
                'status' => Asymptome::STATUS_ACTIVE,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            
            // Asymptômes respiratoires
            [
                'nom' => 'Toux sèche',
                'description' => 'Toux sans expectoration, souvent irritative et persistante.',
                'gravite' => 'légère',
                'status' => Asymptome::STATUS_ACTIVE,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'nom' => 'Dyspnée',
                'description' => 'Difficulté à respirer, sensation d\'essoufflement anormal.',
                'gravite' => 'sévère',
                'status' => Asymptome::STATUS_ACTIVE,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'nom' => 'Sifflements respiratoires',
                'description' => 'Bruits sifflants lors de la respiration, souvent associés à l\'asthme.',
                'gravite' => 'modérée',
                'status' => Asymptome::STATUS_ACTIVE,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            
            // Asymptômes digestifs
            [
                'nom' => 'Nausées',
                'description' => 'Sensation de malaise gastrique avec envie de vomir.',
                'gravite' => 'légère',
                'status' => Asymptome::STATUS_ACTIVE,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'nom' => 'Vomissements',
                'description' => 'Expulsion forcée du contenu gastrique par la bouche.',
                'gravite' => 'modérée',
                'status' => Asymptome::STATUS_ACTIVE,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'nom' => 'Diarrhée',
                'description' => 'Selles liquides fréquentes, plus de 3 fois par jour.',
                'gravite' => 'modérée',
                'status' => Asymptome::STATUS_ACTIVE,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'nom' => 'Douleurs abdominales',
                'description' => 'Douleurs localisées dans la région abdominale, d\'intensité variable.',
                'gravite' => 'modérée',
                'status' => Asymptome::STATUS_ACTIVE,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            
            // Asymptômes neurologiques
            [
                'nom' => 'Céphalées',
                'description' => 'Maux de tête d\'intensité et de localisation variables.',
                'gravite' => 'modérée',
                'status' => Asymptome::STATUS_ACTIVE,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'nom' => 'Vertiges',
                'description' => 'Sensation de déséquilibre, impression que l\'environnement tourne.',
                'gravite' => 'modérée',
                'status' => Asymptome::STATUS_ACTIVE,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'nom' => 'Troubles de la vision',
                'description' => 'Altération de la vision : flou, double vision, perte partielle.',
                'gravite' => 'sévère',
                'status' => Asymptome::STATUS_ACTIVE,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            
            // Asymptômes cardiovasculaires
            [
                'nom' => 'Palpitations',
                'description' => 'Sensation de battements cardiaques rapides ou irréguliers.',
                'gravite' => 'modérée',
                'status' => Asymptome::STATUS_ACTIVE,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'nom' => 'Œdème des membres inférieurs',
                'description' => 'Gonflement des chevilles et des jambes par rétention d\'eau.',
                'gravite' => 'modérée',
                'status' => Asymptome::STATUS_ACTIVE,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            
            // Asymptômes musculo-squelettiques
            [
                'nom' => 'Douleurs articulaires',
                'description' => 'Douleurs localisées au niveau des articulations.',
                'gravite' => 'modérée',
                'status' => Asymptome::STATUS_ACTIVE,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'nom' => 'Raideur matinale',
                'description' => 'Difficulté à bouger les articulations au réveil.',
                'gravite' => 'légère',
                'status' => Asymptome::STATUS_ACTIVE,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'nom' => 'Faiblesse musculaire',
                'description' => 'Diminution de la force musculaire dans un ou plusieurs groupes musculaires.',
                'gravite' => 'modérée',
                'status' => Asymptome::STATUS_ACTIVE,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            
            // Asymptômes dermatologiques
            [
                'nom' => 'Éruption cutanée',
                'description' => 'Apparition de lésions sur la peau : rougeurs, boutons, plaques.',
                'gravite' => 'légère',
                'status' => Asymptome::STATUS_ACTIVE,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'nom' => 'Démangeaisons',
                'description' => 'Sensation désagréable provoquant le besoin de se gratter.',
                'gravite' => 'légère',
                'status' => Asymptome::STATUS_ACTIVE,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            
            // Asymptômes endocriniens
            [
                'nom' => 'Soif excessive',
                'description' => 'Besoin anormal et persistant de boire, souvent signe de diabète.',
                'gravite' => 'modérée',
                'status' => Asymptome::STATUS_ACTIVE,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'nom' => 'Urination fréquente',
                'description' => 'Augmentation anormale de la fréquence des mictions.',
                'gravite' => 'légère',
                'status' => Asymptome::STATUS_ACTIVE,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'nom' => 'Perte de poids inexpliquée',
                'description' => 'Diminution du poids corporel sans régime ni effort particulier.',
                'gravite' => 'sévère',
                'status' => Asymptome::STATUS_ACTIVE,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            
            // Asymptômes psychologiques
            [
                'nom' => 'Tristesse persistante',
                'description' => 'Humeur dépressive qui persiste dans le temps.',
                'gravite' => 'modérée',
                'status' => Asymptome::STATUS_ACTIVE,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'nom' => 'Anxiété',
                'description' => 'État d\'inquiétude et de tension nerveuse.',
                'gravite' => 'modérée',
                'status' => Asymptome::STATUS_ACTIVE,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'nom' => 'Troubles du sommeil',
                'description' => 'Difficultés d\'endormissement, réveils nocturnes ou insomnie.',
                'gravite' => 'légère',
                'status' => Asymptome::STATUS_ACTIVE,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]
        ];

        foreach ($asymptomes as $asymptome) {
            Asymptome::create($asymptome);
        }

        $this->command->info('Asymptômes créés avec succès!');
    }
}
