<?php

namespace Database\Factories;

use App\Models\Edition;
use Illuminate\Database\Eloquent\Factories\Factory;

class EditionFactory extends Factory
{
    protected $model = Edition::class;

    public function definition()
    {
        return [
            'name' => $this->faker->sentence(3),
            'description_fr' => $this->faker->paragraph,
            'description_en' => $this->faker->paragraph,
            'start_date' => $this->faker->dateTimeBetween('+1 month', '+1 year'),
            'end_date' => $this->faker->dateTimeBetween('+2 months', '+1 year 1 month'),
            'place' => $this->faker->city,
            'dossier_sponso' => 'dossiers_sponso/sponsor_'.$this->faker->uuid.'.pdf',
            'images_url' => json_encode([
                'editions/image1_'.$this->faker->uuid.'.jpg',
                'editions/image2_'.$this->faker->uuid.'.jpg'
            ]),
        ];
    }
}