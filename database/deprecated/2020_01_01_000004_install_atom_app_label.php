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
        Schema::create('labels', function (Blueprint $table) {
            $table->id();
            $table->json('name')->nullable();
            $table->string('slug')->nullable();
            $table->string('type')->nullable();
            $table->text('description')->nullable();
            $table->integer('seq')->nullable();
            $table->string('color')->nullable();
            $table->json('data')->nullable();
            $table->boolean('is_locked')->nullable();
            $table->foreignId('image_id')->nullable()->constrained('files')->onDelete('set null');
            $table->foreignId('parent_id')->nullable()->constrained('labels')->onDelete('cascade');
            $table->timestamps();
            $table->json('footprint')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('labels');
    }
};
