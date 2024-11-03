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
            $table->integer('supplier_id')->index();
            $table->string('item_name');
            $table->integer('quantity');
            $table->integer('request_type_id')->index();
            $table->double('amount')->default(0);
            $table->integer('currency_id')->nullable()->index();
            $table->integer('company_id')->index();
            $table->text('reason_code')->nullable();
            $table->integer('status _id')->index();
            $table->smallInteger('priority')->default(0)->index();
            $table->text('comments')->nullable();
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
