<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Formation;
use App\Models\Inscription;
use Illuminate\Support\Facades\Http;
use Illuminate\Foundation\Testing\RefreshDatabase;

/**
 * @group mes-tests
 */

class PaiementTest extends TestCase
{
    use RefreshDatabase;

    public function test_creation_session_stripe()
    {
        $this->markTestSkipped('Simulation Stripe à revoir ultérieurement.');
        
        Http::fake([
            'https://api.stripe.com/v1/checkout/sessions' => Http::response([
                'id' => 'cs_test_123',
                'url' => 'https://checkout.stripe.com/pay/cs_test_123'
            ], 200)
        ]);

        $user = User::factory()->create(['usertype' => 'user']);
        $formation = Formation::factory()->create([
            'stripe_price_id' => 'price_123',
            'prix' => 20000
        ]);
        $inscription = Inscription::factory()
            ->for($user)
            ->for($formation)
            ->create(['status' => 'Accepté']);

        $response = $this->actingAs($user)
                         ->get(route('checkout', $inscription->id));

        $response->assertRedirect('https://checkout.stripe.com/pay/cs_test_123');
        $this->assertDatabaseHas('inscriptions', [
            'id' => $inscription->id,
            'stripe_session_id' => 'cs_test_123'
        ]);
    }

    public function test_retour_paiement_reussi()
    {
        Http::fake([
            'https://api.stripe.com/v1/checkout/sessions/cs_test_123' => Http::response([
                'payment_status' => 'paid',
                'payment_intent' => 'pi_123',
                'amount_total' => 20000
            ], 200)
        ]);

        $user = User::factory()->create(['usertype' => 'user']);
        $formation = Formation::factory()->create(['prix' => 20000]);
        $inscription = Inscription::factory()
            ->for($user)
            ->for($formation)
            ->create([
                'status' => 'Accepté',
                'stripe_session_id' => 'cs_test_123'
            ]);

        $response = $this->actingAs($user)
                         ->get(route('payment.verify', [
                             'session_id' => 'cs_test_123',
                             'inscription' => $inscription->id
                         ]));

        $response->assertViewIs('payment.success');
        $inscription->refresh();
        $this->assertEquals('Payé', $inscription->status);
        $this->assertEquals('complet', $inscription->statut_paiement);
        $this->assertDatabaseHas('paiements', [
            'inscription_id' => $inscription->id,
            'montant' => 20000,
            'mode' => 'carte banquaire',
            'statut' => 'complet'
        ]);
    }
}