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
        Schema::create('import_requests', function (Blueprint $table) {
            $table->id();
            $table->string('shipment_name')->index();
            $table->string('supplier_id')->index();
            $table->string('item_name');
            $table->string('quantity');
            $table->string('request_type_id')->index();
            $table->double('amount')->default(0);
            $table->integer('currency_id')->index();
            $table->string('invoice')->nullable();
            $table->string('shipping_document')->nullable();
            $table->string('duty_paid_gd')->nullable();
            $table->integer('created_by')->index();
            $table->integer('status_id')->index();
            $table->smallInteger('priority')->default(0)->index();
         
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('import_requests');
    }
};
