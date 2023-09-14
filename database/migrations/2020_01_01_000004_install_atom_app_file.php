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
        Schema::create('files', function (Blueprint $table) {
            $table->id();
            $table->ulid()->unique();
            $table->string('name');
            $table->string('mime')->nullable();
            $table->decimal('size', 20, 2)->nullable();
            $table->string('disk')->nullable();
            $table->text('path')->nullable();
            $table->text('url')->nullable();
            $table->integer('width')->nullable();
            $table->integer('height')->nullable();
            $table->string('extension')->nullable();
            $table->string('visibility')->nullable();
            $table->json('data')->nullable();
            $table->foreignId('parent_id')->nullable()->constrained('files')->onDelete('cascade');
            $table->timestamps();
            $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('files');
    }
};
