<?php

namespace Database\Seeders;

use App\Models\Partner;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PartnerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $partners = [
            [
                'name' => 'Dr. Ahmed Ben Ali',
                'type' => Partner::TYPE_DOCTOR,
                'description' => 'Médecin généraliste expérimenté, spécialisé dans la médecine préventive et le suivi des patients chroniques.',
                'email' => 'dr.benali@example.com',
                'phone' => '+216 71 123 456',
                'address' => '123 Avenue Habib Bourguiba, Centre Ville',
                'city' => 'Tunis',
                'postal_code' => '1000',
                'website' => 'https://dr-benali.tn',
                'license_number' => 'MED-TN-2024-001',
                'specialization' => 'Médecine générale',
                'status' => Partner::STATUS_ACTIVE,
                'contact_person' => 'Dr. Ahmed Ben Ali',
                'rating' => 4.8,
                'services' => [
                    'Consultation générale',
                    'Suivi médical',
                    'Certificats médicaux',
                    'Vaccinations'
                ],
                'opening_hours' => [
                    'lundi' => '08:00-17:00',
                    'mardi' => '08:00-17:00',
                    'mercredi' => '08:00-17:00',
                    'jeudi' => '08:00-17:00',
                    'vendredi' => '08:00-17:00',
                    'samedi' => '08:00-13:00',
                    'dimanche' => 'Fermé'
                ],
                'latitude' => 36.8065,
                'longitude' => 10.1815,
            ],
            [
                'name' => 'Fitness Plus Gym',
                'type' => Partner::TYPE_GYM,
                'description' => 'Salle de sport moderne équipée des dernières technologies pour tous vos objectifs fitness.',
                'email' => 'info@fitnessplus.tn',
                'phone' => '+216 71 234 567',
                'address' => '456 Rue de la République, Menzah',
                'city' => 'Tunis',
                'postal_code' => '1004',
                'website' => 'https://fitnessplus.tn',
                'license_number' => 'GYM-TN-2024-002',
                'specialization' => 'Fitness et musculation',
                'status' => Partner::STATUS_ACTIVE,
                'contact_person' => 'Sami Trabelsi',
                'rating' => 4.5,
                'services' => [
                    'Musculation',
                    'Cardio training',
                    'Cours collectifs',
                    'Personal training',
                    'Nutrition sportive'
                ],
                'opening_hours' => [
                    'lundi' => '06:00-23:00',
                    'mardi' => '06:00-23:00',
                    'mercredi' => '06:00-23:00',
                    'jeudi' => '06:00-23:00',
                    'vendredi' => '06:00-23:00',
                    'samedi' => '07:00-22:00',
                    'dimanche' => '08:00-20:00'
                ],
                'latitude' => 36.8625,
                'longitude' => 10.1928,
            ],
            [
                'name' => 'Laboratoire Central',
                'type' => Partner::TYPE_LABORATORY,
                'description' => 'Laboratoire d\'analyses médicales moderne proposant tous types d\'examens biologiques.',
                'email' => 'contact@labcentral.tn',
                'phone' => '+216 71 345 678',
                'address' => '789 Avenue Mohamed V, Belvédère',
                'city' => 'Tunis',
                'postal_code' => '1002',
                'website' => 'https://labcentral.tn',
                'license_number' => 'LAB-TN-2024-003',
                'specialization' => 'Analyses médicales',
                'status' => Partner::STATUS_ACTIVE,
                'contact_person' => 'Dr. Leila Hammami',
                'rating' => 4.7,
                'services' => [
                    'Analyses sanguines',
                    'Analyses d\'urine',
                    'Biochimie',
                    'Hématologie',
                    'Microbiologie',
                    'Sérologie'
                ],
                'opening_hours' => [
                    'lundi' => '07:00-19:00',
                    'mardi' => '07:00-19:00',
                    'mercredi' => '07:00-19:00',
                    'jeudi' => '07:00-19:00',
                    'vendredi' => '07:00-19:00',
                    'samedi' => '07:00-15:00',
                    'dimanche' => 'Fermé'
                ],
                'latitude' => 36.8188,
                'longitude' => 10.1658,
            ],
            [
                'name' => 'Pharmacie Moderne',
                'type' => Partner::TYPE_PHARMACY,
                'description' => 'Pharmacie proposant médicaments, produits de santé et conseils pharmaceutiques personnalisés.',
                'email' => 'info@pharmaciemoderne.tn',
                'phone' => '+216 71 456 789',
                'address' => '321 Rue Mongi Slim, Manar',
                'city' => 'Tunis',
                'postal_code' => '2092',
                'website' => null,
                'license_number' => 'PHA-TN-2024-004',
                'specialization' => 'Pharmacie d\'officine',
                'status' => Partner::STATUS_ACTIVE,
                'contact_person' => 'Dr. Monia Khelifi',
                'rating' => 4.3,
                'services' => [
                    'Médicaments sur ordonnance',
                    'Médicaments sans ordonnance',
                    'Produits de parapharmacie',
                    'Conseils pharmaceutiques',
                    'Matériel médical'
                ],
                'opening_hours' => [
                    'lundi' => '08:00-20:00',
                    'mardi' => '08:00-20:00',
                    'mercredi' => '08:00-20:00',
                    'jeudi' => '08:00-20:00',
                    'vendredi' => '08:00-20:00',
                    'samedi' => '08:00-20:00',
                    'dimanche' => '09:00-13:00'
                ],
                'latitude' => 36.8484,
                'longitude' => 10.1727,
            ],
            [
                'name' => 'Dra. Sarra Nutritionniste',
                'type' => Partner::TYPE_NUTRITIONIST,
                'description' => 'Nutritionniste diplômée spécialisée dans la perte de poids et la nutrition sportive.',
                'email' => 'dra.sarra@nutrition.tn',
                'phone' => '+216 71 567 890',
                'address' => '654 Avenue de la Liberté, Lac',
                'city' => 'Tunis',
                'postal_code' => '1053',
                'website' => 'https://drasarra-nutrition.tn',
                'license_number' => 'NUT-TN-2024-005',
                'specialization' => 'Nutrition et diététique',
                'status' => Partner::STATUS_ACTIVE,
                'contact_person' => 'Dr. Sarra Mejri',
                'rating' => 4.9,
                'services' => [
                    'Consultation nutritionnelle',
                    'Plans alimentaires personnalisés',
                    'Suivi de perte de poids',
                    'Nutrition sportive',
                    'Rééquilibrage alimentaire'
                ],
                'opening_hours' => [
                    'lundi' => '09:00-18:00',
                    'mardi' => '09:00-18:00',
                    'mercredi' => '09:00-18:00',
                    'jeudi' => '09:00-18:00',
                    'vendredi' => '09:00-18:00',
                    'samedi' => '09:00-15:00',
                    'dimanche' => 'Fermé'
                ],
                'latitude' => 36.8278,
                'longitude' => 10.2075,
            ],
            [
                'name' => 'Cabinet Psychologie Bien-être',
                'type' => Partner::TYPE_PSYCHOLOGIST,
                'description' => 'Cabinet de psychologie offrant accompagnement et thérapies pour le bien-être mental.',
                'email' => 'contact@psy-bienetre.tn',
                'phone' => '+216 71 678 901',
                'address' => '987 Rue Ibn Khaldoun, Mutuelle Ville',
                'city' => 'Tunis',
                'postal_code' => '1082',
                'website' => 'https://psy-bienetre.tn',
                'license_number' => 'PSY-TN-2024-006',
                'specialization' => 'Psychologie clinique',
                'status' => Partner::STATUS_ACTIVE,
                'contact_person' => 'Dr. Karim Bouzid',
                'rating' => 4.6,
                'services' => [
                    'Thérapie individuelle',
                    'Thérapie de couple',
                    'Gestion du stress',
                    'Accompagnement psychologique',
                    'Thérapies comportementales'
                ],
                'opening_hours' => [
                    'lundi' => '10:00-19:00',
                    'mardi' => '10:00-19:00',
                    'mercredi' => '10:00-19:00',
                    'jeudi' => '10:00-19:00',
                    'vendredi' => '10:00-19:00',
                    'samedi' => '10:00-16:00',
                    'dimanche' => 'Fermé'
                ],
                'latitude' => 36.7924,
                'longitude' => 10.1233,
            ],
        ];

        foreach ($partners as $partnerData) {
            Partner::create($partnerData);
        }
    }
}
