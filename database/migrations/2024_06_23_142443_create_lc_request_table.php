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
        Schema::create('lc_request', function (Blueprint $table) {
            $table->id();
            $table->string('shipment_name')->index();
            $table->string('supplier_id')->index();
            $table->string('item_name')->nullable();
            $table->string('quantity')->nullable();
            $table->string('payment_terms');
            $table->smallInteger('draft_required')->default(0)->index();
            $table->smallInteger('priority')->default(0)->index();
            $table->integer('created_by')->index();
            $table->integer('updated_by')->nullable()->index();
            $table->integer('status_id')->index();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lc_request');
    }
};
