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
        Schema::create('stock_update_details', function (Blueprint $table) {
            $table->unsignedSmallInteger('id');
            $table->unsignedBigInteger('update_id');
            $table->unsignedBigInteger('product_id');
            $table->integer('stock_before')->default(0);
            $table->integer('quantity')->default(0);
            $table->decimal('cost', 12, 0)->default(0.);
            $table->decimal('price', 12, 0)->default(0.);
            $table->primary(['id', 'update_id']);
            $table->foreign('product_id')->references('id')->on('products')->onDelete('restrict');
            $table->foreign('update_id')->references('id')->on('stock_updates')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stock_update_details');
    }
};
