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
        Schema::create('documents', function (Blueprint $table) {
            $table->id();
            $table->integer('lc_request_id')->index();
            $table->string('performa_invoice')->nullable();
            $table->string('document_1')->nullable();
            $table->string('document_2')->nullable();
            $table->string('document_3')->nullable();
            $table->string('document_4')->nullable();
            $table->string('document_5')->nullable();
            $table->string('bank_name')->nullable();
            $table->string('bank_document')->nullable();
            $table->string('transited_lc_document')->nullable();
            $table->string('transited_lc_number')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('documents');
    }
};
