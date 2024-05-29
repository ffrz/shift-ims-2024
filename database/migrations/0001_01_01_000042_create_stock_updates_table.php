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
        Schema::create('stock_updates', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('id2')->nullable(true)->default(null);
            $table->unsignedTinyInteger('type')->default(0);
            $table->unsignedTinyInteger('status')->default(0);
            $table->unsignedBigInteger('ref_id')->nullable()->default(null);
            $table->date('date');
            $table->datetime('creation_datetime');
            $table->datetime('closing_datetime')->nullable(true)->default(null);
            $table->unsignedBigInteger('creation_uid')->nullable(true)->default(null);
            $table->unsignedBigInteger('closing_uid')->nullable(true)->default(null);
            $table->decimal('total_cost', 12, 0)->default(0.);
            $table->decimal('total_price', 12, 0)->default(0.);
            $table->text('notes')->nullable()->default(null);
            $table->foreign('creation_uid')->references('id')->on('users')->onDelete('set null');
            $table->foreign('closing_uid')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stock_updates');
    }
};
