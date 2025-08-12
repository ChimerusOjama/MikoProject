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
            $table->decimal('montant', 10, 2);
            $table->enum('mode', ['carte', 'virement', 'espèce', 'mobile']);
            $table->string('reference')->unique();
            $table->enum('statut', ['complet', 'partiel', 'en_attente', 'annulé']);
            $table->date('date_paiement');
            $table->string('preuve_path')->nullable()->comment('Chemin vers la preuve de paiement');
            $table->string('stripe_payment_id')->nullable()->comment('ID du paiement Stripe');
            $table->timestamps();
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
