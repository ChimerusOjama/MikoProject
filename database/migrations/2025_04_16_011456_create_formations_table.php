<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('formations', function (Blueprint $table) {
            $table->id();

            $table->string('titre', 255);
            $table->text('description_courte');
            $table->text('description_longue')->nullable();
            $table->string('categorie', 50)->index();
            $table->string('niveau', 20)->index();
            $table->integer('prix')->unsigned()->default(14500)->index()->comment('Prix en FCFA (entier)');
            $table->string('status')->nullable();
            $table->date('date_debut')->nullable();
            $table->date('date_fin')->nullable();
            $table->integer('duree_mois');
            $table->integer('places_disponibles')->nullable();
            $table->string('image_url');
            $table->string('stripe_price_id')->nullable();
            $table->string('stripe_product_id')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('formations');
    }
};