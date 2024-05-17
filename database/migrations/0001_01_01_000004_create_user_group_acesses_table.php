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
        Schema::create('user_group_accesses', function (Blueprint $table) {
            $table->unsignedBigInteger('group_id');
            $table->string('resource');
            $table->boolean('allow')->default(true);
            $table->primary(['group_id', 'resource']);
            $table->foreign('group_id')->references('id')->on('user_groups')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_group_accesses');
    }
};
