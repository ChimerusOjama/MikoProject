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
        Schema::table('formations', function (Blueprint $table) {
            $table->decimal('price', 8, 2)->default(14500)->after('desc');
            $table->string('stripe_price_id')->nullable()->after('price');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('formations', function (Blueprint $table) {
            $table->dropColumn('price');
            $table->dropColumn('stripe_price_id');
        });
    }
};
