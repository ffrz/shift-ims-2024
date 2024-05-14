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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('group_id')->nullable(true)->default(null);
            $table->string('username')->unique();
            $table->string('fullname')->default('');
            $table->string('password')->default('');
            $table->boolean('is_active')->default(false);
            $table->boolean('is_admin')->default(false);
            $table->rememberToken();
            $table->timestamps();
            $table->foreign('group_id')->references('id')->on('user_groups');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
