<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('formations', function (Blueprint $table) {
            $table->id();
            $table->string('titre', 255);
            $table->text('description_courte');
            $table->string('categorie', 50);
            $table->string('niveau', 20);
            $table->integer('prix');
            $table->string('status')->nullable();
            $table->integer('duree_mois');
            $table->string('image_url');
            $table->timestamps();
            $table->index('categorie');
            $table->index('niveau');
            $table->index('prix');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('formations');
    }
};
