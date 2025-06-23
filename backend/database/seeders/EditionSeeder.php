<?php

namespace Database\Seeders;

use App\Models\Edition;
use Illuminate\Database\Seeder;

class EditionSeeder extends Seeder
{
    public function run()
    {
        // Données spécifiques
        $editions = [
            [
                'name' => 'SITE 2025',
                'description_fr' => 'Conférence annuelle sur l\'intelligence artificielle',
                'description_en' => 'Annual conference on artificial intelligence',
                'start_date' => '2024-05-15',
                'end_date' => '2024-05-17',
                'place' => 'Paris, France',
                'dossier_sponso' => 'dossiers_sponso/conf_ia_2024.pdf',
                'images_url' => json_encode([
                    'editions/ia_conf_1.jpg',
                    'editions/ia_conf_2.jpg'
                ]),
            ],
            [
                'name' => 'SITE 2024',
                'description_fr' => 'Forum des technologies émergentes',
                'start_date' => '2024-09-10',
                'end_date' => '2024-09-12',
                'place' => 'Lyon, France',
                'dossier_sponso' => 'dossiers_sponso/tech_forum_2024.pdf',
                'images_url' => json_encode([
                    'editions/tech_forum_1.jpg'
                ]),
            ],
            [
                'name' => 'SITE 2023',
                'description_en' => 'Annual developers conference',
                'start_date' => '2024-11-20',
                'end_date' => '2024-11-22',
                'place' => 'Marseille, France',
                'dossier_sponso' => 'dossiers_sponso/dev_2024.pdf',
                'images_url' => json_encode([]),
            ],
            [
                'name' => 'SITE 2025',
                'description_en' => 'Annual developers conference',
                'start_date' => '2025-11-20',
                'end_date' => '2025-11-22',
                'place' => 'Marseille, France',
                'dossier_sponso' => 'dossiers_sponso/dev_2025.pdf',
                'images_url' => json_encode([]),
            ]
        ];

        foreach ($editions as $edition) {
            Edition::create($edition);
        }

        // Optionnel : générer des données aléatoires supplémentaires
        // Edition::factory()->count(5)->create();
    }
}