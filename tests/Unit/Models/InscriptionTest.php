<?php

namespace Tests\Unit\Models;

use Tests\TestCase;
use App\Models\Inscription;
use App\Models\Formation;
use App\Models\Paiement;
use Illuminate\Foundation\Testing\RefreshDatabase;

class InscriptionTest extends TestCase
{
    use RefreshDatabase;

    public function test_une_inscription_appartient_a_une_formation()
    {
        $formation = Formation::factory()->create();
        $inscription = Inscription::factory()->for($formation)->create();

        $this->assertInstanceOf(Formation::class, $inscription->formation);
        $this->assertEquals($formation->id, $inscription->formation->id);
    }

    public function test_une_inscription_peut_avoir_plusieurs_paiements()
    {
        $inscription = Inscription::factory()->create();
        Paiement::factory()->count(2)->for($inscription)->create();

        $this->assertCount(2, $inscription->paiements);
    }

    public function test_montant_total_est_celui_de_la_formation_liee()
    {
        $formation = Formation::factory()->create(['prix' => 20000]);
        $inscription = Inscription::factory()->for($formation)->create();

        $this->assertEquals(20000, $inscription->montant_total);
        $this->assertEquals('20 000 FCFA', $inscription->formatted_montant_total);
    }

    public function test_montant_paye_est_la_somme_des_paiements_non_annules()
    {
        $inscription = Inscription::factory()->create();
        Paiement::factory()->for($inscription)->create(['montant' => 5000, 'statut' => 'partiel']);
        Paiement::factory()->for($inscription)->create(['montant' => 3000, 'statut' => 'partiel']);
        Paiement::factory()->for($inscription)->create(['montant' => 2000, 'statut' => 'annulé']); // ignoré

        $this->assertEquals(8000, $inscription->montant_paye);
    }

    public function test_une_inscription_est_payee_totalement_si_montant_paye_egal_montant_total()
    {
        $formation = Formation::factory()->create(['prix' => 10000]);
        $inscription = Inscription::factory()->for($formation)->create();
        Paiement::factory()->for($inscription)->create(['montant' => 10000, 'statut' => 'complet']);

        $this->assertTrue($inscription->isCompletelyPaid());
    }

    public function test_une_inscription_n_est_pas_payee_totalement_si_montant_paye_inferieur()
    {
        $formation = Formation::factory()->create(['prix' => 10000]);
        $inscription = Inscription::factory()->for($formation)->create();
        Paiement::factory()->for($inscription)->create(['montant' => 6000, 'statut' => 'partiel']);

        $this->assertFalse($inscription->isCompletelyPaid());
    }
}