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
        Schema::create('clearance_requests', function (Blueprint $table) {
            $table->id();
            $table->integer('lc_request_id')->index();
            $table->string('bill_number');
            $table->smallInteger('shipment_type_id')->index();
            $table->double('tax');
            $table->string('tracking_number')->nullable();
            $table->date('shipment_date')->nullable();
            $table->date('expected_arrival_date')->nullable();
            $table->string('document')->nullable();
            $table->string('picture')->nullable();
            $table->integer('status_id')->index();
            $table->integer('created_by')->index();
            $table->integer('updated_by')->index()->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('clearance_requests');
    }
};
