<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Ajout des colonnes de suivi spécifiques à PawaPay
        Schema::table('paiements', function (Blueprint $table) {
            $table->string('pawapay_payment_id')->nullable()->after('stripe_payment_id')->comment('ID de transaction renvoyé par PawaPay');
            $table->string('failure_code')->nullable()->after('pawapay_payment_id')->comment('Code d\'échec si le paiement mobile money échoue');
        });

        // 2. Mise à jour des contraintes CHECK pour PostgreSQL
        // On supprime l'ancienne contrainte limitante et on recrée la nouvelle avec 'en_attente'
        DB::statement("ALTER TABLE paiements DROP CONSTRAINT IF EXISTS paiements_statut_check");
        DB::statement("ALTER TABLE paiements ADD CONSTRAINT paiements_statut_check CHECK (statut IN ('complet', 'partiel', 'annulé', 'en_attente'))");

        // Idem pour le type de paiement, on ajoute 'pawapay'
        DB::statement("ALTER TABLE paiements DROP CONSTRAINT IF EXISTS paiements_type_paiement_check");
        DB::statement("ALTER TABLE paiements ADD CONSTRAINT paiements_type_paiement_check CHECK (type_paiement IN ('stripe', 'manuel', 'pawapay'))");
        
        // On s'assure que la valeur par défaut reste 'manuel'
        DB::statement("ALTER TABLE paiements ALTER COLUMN type_paiement SET DEFAULT 'manuel'");
    }

    public function down(): void
    {
        // En cas de rollback, on supprime les colonnes ajoutées
        Schema::table('paiements', function (Blueprint $table) {
            $table->dropColumn(['pawapay_payment_id', 'failure_code']);
        });

        // Et on remet les contraintes de vérification d'origine
        DB::statement("ALTER TABLE paiements DROP CONSTRAINT IF EXISTS paiements_statut_check");
        DB::statement("ALTER TABLE paiements ADD CONSTRAINT paiements_statut_check CHECK (statut IN ('complet', 'partiel', 'annulé'))");

        DB::statement("ALTER TABLE paiements DROP CONSTRAINT IF EXISTS paiements_type_paiement_check");
        DB::statement("ALTER TABLE paiements ADD CONSTRAINT paiements_type_paiement_check CHECK (type_paiement IN ('stripe', 'manuel'))");
    }
};