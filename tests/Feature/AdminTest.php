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

class AdminTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_peut_creer_une_formation()
    {
        $admin = User::factory()->create(['usertype' => 'admin']);

        $response = $this->actingAs($admin)
                         ->post(route('storeForm'), [
                             'titre' => 'Formation test',
                             'description_courte' => 'Description courte',
                             'description_longue' => 'Description longue',
                             'categorie' => 'developpement',
                             'niveau' => 'debutant',
                             'prix' => 25000,
                             'duree_mois' => 3,
                             'places_disponibles' => 20,
                             'stripe_price_id' => 'price_123',
                             'stripe_product_id' => 'prod_123',
                             'status' => 'publiee',
                             'date_debut' => '2026-03-01',
                             'date_fin' => '2026-06-01',
                             'image' => \Illuminate\Http\UploadedFile::fake()->image('formation.jpg')
                         ]);

        $response->assertSessionHas('success');
        $this->assertDatabaseHas('formations', ['titre' => 'Formation test']);
    }

    public function test_admin_peut_supprimer_une_formation_sans_inscription()
    {
        $admin = User::factory()->create(['usertype' => 'admin']);
        $formation = Formation::factory()->create();

        $response = $this->actingAs($admin)
                         ->get(route('supForm', $formation->id));

        $response->assertSessionHas('success');
        $this->assertDatabaseMissing('formations', ['id' => $formation->id]);
    }

    public function test_admin_ne_peut_pas_supprimer_une_formation_avec_inscriptions()
    {
        $admin = User::factory()->create(['usertype' => 'admin']);
        $formation = Formation::factory()->create();
        Inscription::factory()->for($formation)->create();

        $response = $this->actingAs($admin)
                         ->get(route('supForm', $formation->id));

        $response->assertSessionHas('warning');
        $this->assertDatabaseHas('formations', ['id' => $formation->id]);
    }
}