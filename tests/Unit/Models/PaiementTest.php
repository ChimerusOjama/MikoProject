<?php

namespace Tests\Unit\Models;

use Tests\TestCase;
use App\Models\Paiement;
use App\Models\Inscription;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PaiementTest extends TestCase
{
    use RefreshDatabase;

    public function test_un_paiement_appartient_a_une_inscription()
    {
        $inscription = Inscription::factory()->create();
        $paiement = Paiement::factory()->for($inscription)->create();

        $this->assertInstanceOf(Inscription::class, $paiement->inscription);
        $this->assertEquals($inscription->id, $paiement->inscription->id);
    }

    public function test_un_paiement_a_un_montant_formate()
    {
        $paiement = Paiement::factory()->make(['montant' => 7500]);
        $this->assertEquals('7 500 FCFA', $paiement->formatted_montant);
    }

    public function test_un_paiement_peut_etre_un_account()
    {
        $paiement = Paiement::factory()->make(['account_type' => 'account_1']);
        $this->assertTrue($paiement->isAccount());

        $paiement = Paiement::factory()->make(['account_type' => 'principal']);
        $this->assertFalse($paiement->isAccount());
    }

    public function test_un_paiement_a_un_label_pour_le_mode()
    {
        $paiement = Paiement::factory()->make(['mode' => 'especes']);
        $this->assertEquals('Espèces', $paiement->mode_label);

        $paiement = Paiement::factory()->make(['mode' => 'carte banquaire']);
        $this->assertEquals('Carte bancaire', $paiement->mode_label);
    }
}