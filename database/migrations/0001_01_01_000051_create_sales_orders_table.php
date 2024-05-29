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
        Schema::create('sales_orders', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('stock_update_id');
            $table->tinyInteger('status')->default(0); // 0
            $table->tinyInteger('paid')->default(0);
            $table->date('date')->nullable()->default(null);
            $table->unsignedBigInteger('customer_id')->nullable()->default(null);
            $table->string('customer_name')->default('');
            $table->string('customer_phone')->default('');
            $table->string('customer_address')->default('');
            $table->decimal('total', 12, 0)->default(0.);
            $table->decimal('total_receivable', 12, 0)->default(0.);
            $table->text('notes')->nullable(true)->default(null);
            $table->timestamps();
            $table->foreign('customer_id')->references('id')->on('parties')->onDelete('set null');
            $table->foreign('stock_update_id')->references('id')->on('stock_updates');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sales_orders');
    }
};
