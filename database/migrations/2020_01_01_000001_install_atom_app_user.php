<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public $rootUsername = 'root';
    public $rootEmail = 'root@jiannius.com';
    public $rootPassword = 'password';

    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::statement('ALTER TABLE users MODIFY COLUMN email_verified_at TIMESTAMP AFTER remember_token');
        
        Schema::table('users', function (Blueprint $table) {
            $table->string('username')->nullable()->after('name');
            $table->string('email')->nullable()->change();
            $table->string('password')->nullable()->change();
            $table->timestamp('last_active_at')->nullable()->after('remember_token');
            $table->timestamp('login_at')->nullable()->after('remember_token');
            $table->string('status')->nullable()->after('remember_token');
            $table->json('data')->nullable()->after('remember_token');
            $table->string('tier')->nullable()->after('remember_token');
            $table->timestamp('blocked_at')->nullable();
            $table->timestamp('deleted_at')->nullable();
            $table->json('footprint')->nullable();
        });

        Schema::create('user_settings', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->json('value')->nullable();
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('cascade');
        });

        Schema::create('verifications', function (Blueprint $table) {
            $table->id();
            $table->string('code')->nullable();
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->timestamp('expired_at')->nullable();
            $table->timestamps();
        });

        // create root user
        if (!model('user')->where('email', $this->rootEmail)->count()) {
            model('user')->forceFill([
                'name' => 'Root',
                'username' => $this->rootUsername,
                'email' => $this->rootEmail,
                'password' => bcrypt($this->rootPassword),
                'status' => 'active',
                'tier' => 'root',
                'email_verified_at' => now(),
            ])->save();
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::table('users')->where('email', $this->rootEmail)->delete();

        Schema::dropIfExists('verifications');
        Schema::dropIfExists('user_settings');

        Schema::table('users', function(Blueprint $table) {
            $table->dropColumn('last_active_at');
            $table->dropColumn('login_at');
            $table->dropColumn('tier');
            $table->dropColumn('status');
            $table->dropColumn('data');
            $table->dropColumn('blocked_at');
            $table->dropColumn('deleted_at');
            $table->dropColumn('footprint');
        });
    }
};
