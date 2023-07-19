<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public $rootEmail = 'root@jiannius.com';
    public $rootPassword = 'password';

    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('password')->nullable()->change();
            $table->timestamp('last_active_at')->nullable()->after('remember_token');
            $table->timestamp('login_at')->nullable()->after('remember_token');
            $table->timestamp('activated_at')->nullable()->after('remember_token');
            $table->timestamp('onboarded_at')->nullable()->after('remember_token');
            $table->timestamp('signup_at')->nullable()->after('remember_token');
            $table->boolean('is_root')->nullable()->after('remember_token');
            $table->string('status')->nullable()->after('remember_token');
            $table->string('visibility')->nullable()->after('remember_token');
            $table->json('data')->nullable()->after('remember_token');
            $table->timestamp('blocked_at')->nullable();
            $table->timestamp('deleted_at')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('blocked_by')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('deleted_by')->nullable()->constrained('users')->onDelete('set null');
        });

        Schema::create('user_settings', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->json('value')->nullable();
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('cascade');
        });

        // create root user
        if (!DB::table('users')->where('email', $this->rootEmail)->count()) {
            DB::table('users')->insert([
                'name' => 'Root',
                'email' => $this->rootEmail,
                'password' => bcrypt($this->rootPassword),
                'visibility' => 'global',
                'status' => 'active',
                'is_root' => true,
                'email_verified_at' => now(),
                'activated_at' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::table('users')->where('email', $this->rootEmail)->delete();

        Schema::dropIfExists('user_settings');

        Schema::table('users', function(Blueprint $table) {
            $table->dropColumn('last_active_at');
            $table->dropColumn('login_at');
            $table->dropColumn('activated_at');
            $table->dropColumn('onboarded_at');
            $table->dropColumn('signup_at');
            $table->dropColumn('is_root');
            $table->dropColumn('status');
            $table->dropColumn('visibility');
            $table->dropColumn('data');
            $table->dropColumn('blocked_at');
            $table->dropColumn('deleted_at');

            $table->dropForeign(['created_by']);
            $table->dropColumn('created_by');

            $table->dropForeign(['blocked_by']);
            $table->dropColumn('blocked_by');
            
            $table->dropForeign(['deleted_by']);
            $table->dropColumn('deleted_by');
        });
    }
};
