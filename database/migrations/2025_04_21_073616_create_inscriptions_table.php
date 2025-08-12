<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
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
            $table->string('choixForm');
            $table->integer('montant')->unsigned();
            $table->text('message')->nullable();
            $table->string('status')->default('en_attente')->comment('Statut de l\'inscription: en_attente, accepté, rejeté');
            $table->enum('statut_paiement', [
                'non_payé', 
                'acompte', 
                'complet'
            ])->default('non_payé')->comment('Statut du paiement');
            $table->string('stripe_session_id')->nullable()->comment('ID de session Stripe pour paiement en ligne');
            $table->timestamps();
            $table->index('status');
            $table->index('statut_paiement');
            $table->index('created_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('inscriptions');
    }
};