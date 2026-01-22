<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('paiements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('inscription_id')->constrained()->onDelete('cascade');
            $table->integer('montant')->unsigned()->comment('Montant du paiement en FCFA (entier)');
            $table->enum('mode', ['mobile money', 'carte banquaire', 'airtel money', 'especes'])->comment('Mode de paiement');
            $table->string('reference')->unique();
            $table->enum('statut', ['complet', 'partiel', 'annulé']);
            $table->enum('account_type', ['principal', 'account_1', 'account_2'])->default('principal')
                  ->comment('Type de compte: principal (paiement unique), account_1 (premier account), account_2 (deuxième account)');
            $table->enum('type_paiement', ['stripe', 'manuel'])->default('manuel')->comment('Type de paiement: stripe (en ligne) ou manuel');
            $table->dateTime('date_paiement');
            $table->string('preuve_path')->nullable()->comment('Chemin vers la preuve de paiement');
            $table->string('stripe_payment_id')->nullable()->comment('ID du paiement Stripe');
            $table->timestamps();
            
            // Index pour optimiser les requêtes
            $table->index(['inscription_id', 'account_type']);
            $table->index('type_paiement');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('paiements');
    }
};