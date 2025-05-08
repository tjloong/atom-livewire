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
            $table->string('email')->nullable()->change();
            $table->string('password')->nullable()->change();

            if (!Schema::hasColumn('users', 'username'))
                $table->string('username')->nullable()->after('name');

            if (!Schema::hasColumn('users', 'last_active_at'))
                $table->timestamp('last_active_at')->nullable()->after('remember_token');

            if (!Schema::hasColumn('users', 'login_at'))
                $table->timestamp('login_at')->nullable()->after('remember_token');

            if (!Schema::hasColumn('users', 'status'))
                $table->string('status')->nullable()->after('remember_token');

            if (!Schema::hasColumn('users', 'data'))
                $table->json('data')->nullable()->after('remember_token');

            if (!Schema::hasColumn('users', 'settings'))
                $table->json('settings')->nullable()->after('remember_token');

            if (!Schema::hasColumn('users', 'tier'))
                $table->string('tier')->nullable()->after('remember_token');

            if (!Schema::hasColumn('users', 'blocked_at'))
                $table->timestamp('blocked_at')->nullable();

            if (!Schema::hasColumn('users', 'deleted_at'))
                $table->timestamp('deleted_at')->nullable();
        });

        if (!Schema::hasTable('passcodes')) {
            Schema::create('passcodes', function (Blueprint $table) {
                $table->ulid('id')->primary();
                $table->string('code')->nullable();
                $table->string('email')->nullable();
                $table->string('phone')->nullable();
                $table->timestamp('expired_at')->nullable();
                $table->timestamps();
            });
        }

        // create root user
        if (!DB::table('users')->where('email', $this->rootEmail)->count()) {
            DB::table('users')->insert([
                'name' => 'Root',
                'username' => $this->rootUsername,
                'email' => $this->rootEmail,
                'password' => bcrypt($this->rootPassword),
                'status' => 'active',
                'tier' => 'root',
                'email_verified_at' => now(),
            ]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::table('users')->where('email', $this->rootEmail)->delete();

        Schema::dropIfExists('verifications');

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
