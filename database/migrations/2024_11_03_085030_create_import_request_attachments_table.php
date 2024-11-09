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
        Schema::create('import_request_attachments', function (Blueprint $table) {
            $table->id();
            $table->integer('import_request_id')->index();
            $table->string('invoice')->nullable();
            $table->string('shipping_document')->nullable();
            $table->string('duty_paid_gd')->nullable();
            $table->string('bank_name')->nullable();
            $table->string('bank_document')->nullable();
            $table->string('payment_support')->nullable();
            $table->string('bank_endorsed_document')->nullable();
            $table->string('fi_number_screenshot')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('import_request_attachments');
    }
};
