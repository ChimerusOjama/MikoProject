<?php

namespace Tests\Unit\Models;

use Tests\TestCase;
use App\Models\Formation;
use App\Models\Inscription;
use Illuminate\Foundation\Testing\RefreshDatabase;

class FormationTest extends TestCase
{
    use RefreshDatabase;

    public function test_une_formation_avec_places_disponibles_est_inscriptible()
    {
        $formation = Formation::factory()->create(['places_disponibles' => 5]);
        $this->assertTrue($formation->places_disponibles > 0);
    }

    public function test_une_formation_sans_places_n_est_pas_inscriptible()
    {
        $formation = Formation::factory()->create(['places_disponibles' => 0]);
        $this->assertFalse($formation->places_disponibles > 0);
    }

    public function test_une_formation_peut_avoir_plusieurs_inscriptions()
    {
        $formation = Formation::factory()->create();
        Inscription::factory()->count(3)->for($formation)->create();

        $this->assertCount(3, $formation->inscriptions);
    }

    public function test_une_formation_a_un_prix_formate()
    {
        $formation = Formation::factory()->create(['prix' => 15000]);
        $this->assertEquals('15 000 FCFA', $formation->formatted_prix);
    }

    // public function test_une_formation_a_une_date_debut_formatee()
    // {
    //     $formation = Formation::factory()->create(['date_debut' => '2024-07-01']);
    //     $this->assertEquals('1 juillet 2024', $formation->formatted_date_debut);
    // }

    // public function test_une_formation_a_une_date_fin_formatee()
    // {
    //     $formation = Formation::factory()->create(['date_fin' => '2024-07-10']);
    //     $this->assertEquals('10 juillet 2024', $formation->formatted_date_fin);
    // }
}