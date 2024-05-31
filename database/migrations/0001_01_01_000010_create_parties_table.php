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
        Schema::create('parties', function (Blueprint $table) {
            $table->id();
            $table->unsignedTinyInteger('type');
            $table->unsignedInteger('id2');
            $table->boolean('active')->default(true);
            $table->string('name', 100);
            $table->string('phone', 100)->default('');
            $table->text('address')->nullable()->default(null);
            $table->text('notes')->nullable()->default(null);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('parties');
    }
};
