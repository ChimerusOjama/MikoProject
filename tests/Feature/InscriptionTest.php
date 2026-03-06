<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Formation;
use App\Models\Inscription;
use Illuminate\Foundation\Testing\RefreshDatabase;

/**
 * @group mes-tests
 */

class InscriptionTest extends TestCase
{
    use RefreshDatabase;

    public function test_un_utilisateur_connecte_peut_sinscrire_a_une_formation()
    {
        $user = User::factory()->create(['usertype' => 'user']);
        $formation = Formation::factory()->create([
            'status' => 'publiee',
            'prix' => 15000
        ]);

        $response = $this->actingAs($user)
                         ->post(route('inscForm'), [
                             'formation_id' => $formation->id,
                             'formation_prix' => 15000,
                             'message' => 'Je suis intéressé'
                         ]);

        $response->assertSessionHas('success');
        $this->assertDatabaseHas('inscriptions', [
            'user_id' => $user->id,
            'formation_id' => $formation->id,
            'status' => 'Accepté',
            'message' => 'Je suis intéressé'
        ]);
    }

    public function test_un_utilisateur_ne_peut_pas_sinscrire_deux_fois_a_la_meme_formation_active()
    {
        $user = User::factory()->create(['usertype' => 'user']);
        $formation = Formation::factory()->create(['status' => 'publiee']);

        // Première inscription
        Inscription::factory()->for($user)->for($formation)->create(['status' => 'Accepté']);

        // Tentative de seconde inscription
        $response = $this->actingAs($user)
                         ->post(route('inscForm'), [
                             'formation_id' => $formation->id,
                             'formation_prix' => $formation->prix
                         ]);

        $response->assertSessionHas('warning');
        $this->assertCount(1, Inscription::where('user_id', $user->id)->where('formation_id', $formation->id)->get());
    }

    public function test_un_utilisateur_peut_annuler_son_inscription_si_non_payee()
    {
        $user = User::factory()->create(['usertype' => 'user']);
        $inscription = Inscription::factory()
            ->for($user)
            ->for(Formation::factory())
            ->create(['status' => 'Accepté', 'statut_paiement' => null]);

        $response = $this->actingAs($user)
                         ->post(route('annuler.inscription', $inscription->id));

        $response->assertSessionHas('success');
        $this->assertDatabaseHas('inscriptions', [
            'id' => $inscription->id,
            'status' => 'Annulé'
        ]);
    }
}