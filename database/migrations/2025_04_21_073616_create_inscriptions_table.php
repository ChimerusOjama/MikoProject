<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Crée la table des inscriptions avec toutes les colonnes nécessaires
     */
    public function up(): void
    {
        Schema::create('inscriptions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null');
            $table->foreignId('formation_id')->nullable()->constrained()->onDelete('set null');
            $table->string('name');
            $table->string('email');
            $table->string('phone');
            $table->string('address');
            $table->integer('montant')->unsigned();
            $table->string('stripe_session_id')->nullable();
            $table->string('receipt_path')->nullable()->comment('Chemin vers le reçu de paiement');
            $table->string('choixForm');
            $table->text('message')->nullable();
            $table->string('status')->nullable()->default('accepté')->comment('Statut de l\'inscription : en attente, accepté, rejeté');
            $table->timestamps();
            $table->index('status');
            $table->index('created_at');
        });
    }

    /**
     * Supprime la table des inscriptions
     */
    public function down(): void
    {
        Schema::dropIfExists('inscriptions');
    }
};