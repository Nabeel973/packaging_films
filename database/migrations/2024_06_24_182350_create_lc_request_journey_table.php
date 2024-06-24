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
        Schema::create('lc_request_journey', function (Blueprint $table) {
            $table->id();
            $table->integer('lc_request_id')->index();
            $table->integer('user_id')->index();
            $table->integer('status_id')->index();
            $table->text('reason_code')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lc_request_journey');
    }
};
