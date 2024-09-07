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
            $table->string('payment_terms')->nullable()->change();
            $table->string('payment_id')->nullable()->index();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('lc_request', function (Blueprint $table) {
            $table->string('payment_terms')->nullable(false)->change();
            $table->dropColumn('payment_id');
        });
    }
};
