<?php

namespace Database\Factories;

use App\Models\Paiement;
use App\Models\Inscription;
use Illuminate\Database\Eloquent\Factories\Factory;

class PaiementFactory extends Factory
{
    protected $model = Paiement::class;

    public function definition(): array
    {
        return [
            'inscription_id' => Inscription::factory(),
            'montant' => $this->faker->numberBetween(5000, 500000),
            'mode' => $this->faker->randomElement(['mobile money', 'carte banquaire', 'airtel money', 'especes']),
            'reference' => 'REF-' . strtoupper($this->faker->bothify('??####')),
            'statut' => $this->faker->randomElement(['complet', 'partiel', 'annulé']),
            'account_type' => $this->faker->randomElement(['principal', 'account_1', 'account_2']),
            'type_paiement' => $this->faker->randomElement(['stripe', 'manuel']),
            'date_paiement' => $this->faker->dateTimeBetween('-1 month', 'now'),
            'preuve_path' => null,
            'stripe_payment_id' => null,
        ];
    }

    public function stripe(): static
    {
        return $this->state(fn (array $attributes) => [
            'type_paiement' => 'stripe',
            'reference' => 'STRIPE_' . $this->faker->regexify('[A-Za-z0-9]{20}'),
        ]);
    }

    public function manuel(): static
    {
        return $this->state(fn (array $attributes) => [
            'type_paiement' => 'manuel',
        ]);
    }
}