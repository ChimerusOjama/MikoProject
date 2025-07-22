<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('inscriptions', function (Blueprint $table) {
            $table->string('receipt_path')->nullable()->after('stripe_session_id')->nullable()->comment('Path to the receipt file for the inscription');
        });
    }

    public function down()
    {
        Schema::table('inscriptions', function (Blueprint $table) {
            $table->dropColumn('receipt_path');
        });
    }

};
