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
        Schema::table('lc_request_journey', function (Blueprint $table) {
            // $table->integer('amendment_request_id')->nullable()->index();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('lc_request_journey', function (Blueprint $table) {
            // $table->dropColumn('amendment_request_id');
        });
    }
};
