<?php

namespace Database\Factories;

use App\Models\Inscription;
use App\Models\User;
use App\Models\Formation;
use Illuminate\Database\Eloquent\Factories\Factory;

class InscriptionFactory extends Factory
{
    protected $model = Inscription::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'formation_id' => Formation::factory(),
            'name' => $this->faker->name(),
            'email' => $this->faker->safeEmail(),
            'phone' => $this->faker->phoneNumber(),
            'address' => $this->faker->address(),
            'choixForm' => $this->faker->sentence(3),
            'message' => $this->faker->optional()->sentence(),
            'status' => 'Accepté',
            'statut_paiement' => 'non_payé',   // <-- Ajout
            'stripe_session_id' => null,
            'payment_date' => null,
            'date_annulation' => null,
        ];
    }

    public function enAttente(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'En attente',
        ]);
    }

    public function acceptee(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'Accepté',
        ]);
    }

    public function payee(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'Payé',
            'statut_paiement' => 'complet',
        ]);
    }

    public function annulee(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'Annulé',
            'date_annulation' => now(),
        ]);
    }
}