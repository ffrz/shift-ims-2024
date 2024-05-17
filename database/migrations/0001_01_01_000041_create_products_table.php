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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('category_id')->nullable(true)->default(null);
            $table->unsignedTinyInteger('type');
            $table->boolean('active')->default(true);
            $table->string('code')->unique();
            $table->string('description');
            $table->string('barcode');
            $table->string('uom');
            $table->integer('stock');
            $table->decimal('cost');
            $table->decimal('price');
            $table->text('notes');
            $table->timestamps();
            $table->softDeletes();
            $table->foreign('category_id')->references('id')->on('product_categories');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
