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
        Schema::create('banners', function (Blueprint $table) {
            $table->id();
            $table->ulid()->unique();
            $table->string('type')->nullable();
            $table->string('name')->nullable();
            $table->string('slug')->nullable();
            $table->text('description')->nullable();
            $table->json('placement')->nullable();
            $table->string('url')->nullable();
            $table->integer('seq')->nullable();
            $table->boolean('is_active')->nullable();
            $table->foreignId('image_id')->nullable()->constrained('files')->onDelete('set null');
            $table->foreignId('mob_image_id')->nullable()->constrained('files')->onDelete('set null');
            $table->date('start_at')->nullable();
            $table->date('end_at')->nullable();
            $table->timestamps();
            $table->json('footprint')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('banners');
    }
};
