<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('pages', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('title')->nullable();
            $table->string('slug')->nullable();
            $table->string('locale')->nullable();
            $table->longText('content')->nullable();
            $table->json('seo')->nullable();
            $table->json('data')->nullable();
            $table->timestamps();
        });

        foreach ([
            ['name' => 'Privacy', 'title' => 'Privacy Policy', 'slug' => 'privacy'],
            ['name' => 'Terms', 'title' => 'Terms and Conditions', 'slug' => 'terms'],
        ] as $page) {
            DB::table('pages')->insert(array_merge($page, [
                'locale' => 'en',
                'created_at' => now(),
                'updated_at' => now(),
            ]));
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pages');
    }
};
