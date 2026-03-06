<?php

namespace Database\Factories;

use App\Models\Formation;
use Illuminate\Database\Eloquent\Factories\Factory;

class FormationFactory extends Factory
{
    protected $model = Formation::class;

    public function definition(): array
    {
        return [
            'titre' => $this->faker->sentence(3),
            'description_courte' => $this->faker->sentence(10),
            'description_longue' => $this->faker->paragraphs(3, true),
            'categorie' => $this->faker->randomElement(['developpement', 'bureautique', 'gestion', 'langues', 'marketing', 'design']),
            'niveau' => $this->faker->randomElement(['debutant', 'intermediaire', 'avance']),
            'prix' => $this->faker->numberBetween(5000, 500000),
            'status' => 'publiee',
            'date_debut' => $this->faker->dateTimeBetween('+1 month', '+2 months'),
            'date_fin' => $this->faker->dateTimeBetween('+3 months', '+6 months'),
            'duree_mois' => $this->faker->numberBetween(1, 12),
            'places_disponibles' => $this->faker->numberBetween(0, 30),
            'image_url' => '/storage/imgsFormation/default.jpg',
            'stripe_price_id' => 'price_' . $this->faker->regexify('[A-Za-z0-9]{10}'),
            'stripe_product_id' => 'prod_' . $this->faker->regexify('[A-Za-z0-9]{10}'),
        ];
    }

    public function archivee(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'archivee',
        ]);
    }
}