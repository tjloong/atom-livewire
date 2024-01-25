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
        Schema::create('notifications', function (Blueprint $table) {
            $table->id();
            $table->ulid()->unique();
            $table->string('channel')->nullable();
            $table->string('subject')->nullable();
            $table->string('greeting')->nullable();
            $table->longText('body')->nullable();
            $table->json('data')->nullable();
            $table->string('status')->nullable();
            $table->timestamps();
            $table->json('footprint')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notifications');
    }
};
