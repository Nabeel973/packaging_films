<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('lc_request', function (Blueprint $table) {
            $table->double('amount')->default(0);
            $table->integer('currency_id')->index();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('lc_request', function (Blueprint $table) {
            $table->dropColumn('amount');
            $table->dropColumn('currency_id');
        });
    }
};
