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
        if (Schema::hasTable('signups')) return;

        Schema::create('signups', function (Blueprint $table) {
            $table->id();
            $table->string('refcode')->nullable();
            $table->json('utm')->nullable();
            $table->json('geo')->nullable();
            $table->string('status')->nullable();
            $table->string('method')->nullable();
            $table->json('data')->nullable();
            $table->boolean('agree_tnc')->nullable();
            $table->boolean('agree_promo')->nullable();
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('cascade');
            $table->timestamp('onboarded_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('signups');
    }
};
