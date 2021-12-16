<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class CreateAtomTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $this->modifyUsersTable();
        $this->createTeamsTables();
        $this->createRolesTables();
        $this->createAbilitiesTables();
        $this->createFilesTable();
        $this->createLabelsTable();
        $this->createBlogsTable();
        $this->createPagesTable();
        $this->createEnquiriesTable();
        $this->createSiteSettingsTable();
        $this->createRootUser();
    }

    /**
     * Modify users table
     */
    public function modifyUsersTable($up = true)
    {
        if ($up) {
            Schema::table('users', function (Blueprint $table) {
                $table->string('password')->nullable()->change();
                $table->string('status')->nullable()->after('password');
                $table->unsignedBigInteger('created_by')->nullable();

                $table->foreign('created_by')->references('id')->on('users')->onDelete('set null');
            });
        }
        else {
            Schema::table('users', function (Blueprint $table) {
                $table->string('password')->change();
                $table->dropColumn('status');
            });    
        }
    }

    /**
     * Create teams tables
     * 
     * @return void
     */
    public function createTeamsTables($up = true)
    {
        if ($up) {
            Schema::create('teams', function (Blueprint $table) {
                $table->id();
                $table->string('name')->nullable();
                $table->text('description')->nullable();
                $table->timestamps();
                $table->unsignedBigInteger('created_by')->nullable();

                $table->foreign('created_by')->references('id')->on('users')->onDelete('set null');
            });
    
            Schema::create('teams_users', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('team_id')->nullable();
                $table->unsignedBigInteger('user_id')->nullable();
    
                $table->foreign('team_id')->references('id')->on('teams')->onDelete('cascade');
                $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            });    
        }
        else {
            Schema::dropIfExists('teams_users');
            Schema::dropIfExists('teams');
        }
    }

    /**
     * Create roles table
     * 
     * @return void
     */
    public function createRolesTables($up = true)
    {
        if ($up) {
            Schema::create('roles', function (Blueprint $table) {
                $table->id();
                $table->string('name')->nullable();
                $table->string('slug')->nullable();
                $table->string('scope')->nullable();
                $table->boolean('is_system')->nullable();
                $table->timestamps();
                $table->unsignedBigInteger('created_by')->nullable();

                $table->foreign('created_by')->references('id')->on('users')->onDelete('set null');
            });
    
            Schema::table('users', function(Blueprint $table) {
                $table->unsignedBigInteger('role_id')->nullable()->after('remember_token');
                $table->foreign('role_id')->references('id')->on('roles')->onDelete('set null');
            });
    
            DB::table('roles')->insert([
                [
                    'name' => 'Root',
                    'slug' => 'root',
                    'scope' => 'root',
                    'is_system' => true,
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'name' => 'Administrator', 
                    'slug' => 'administrator',
                    'scope' => 'global',
                    'is_system' => true,
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'name' => 'Restricted User', 
                    'slug' => 'restricted-user',
                    'scope' => 'restrict',
                    'is_system' => true,
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
            ]);
        }
        else {
            Schema::table('users', function(Blueprint $table) {
                $table->dropForeign(['role_id']);
                $table->dropColumn('role_id');
            });
    
            Schema::dropIfExists('roles');    
        }
    }

    /**
     * Create abilities tables
     * 
     * @return void
     */
    public function createAbilitiesTables($up = true)
    {
        if ($up) {
            Schema::create('abilities', function (Blueprint $table) {
                $table->id();
                $table->string('module')->nullable();
                $table->string('name')->nullable();
                $table->timestamps();
            });
    
            Schema::create('abilities_roles', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('ability_id')->nullable();
                $table->unsignedBigInteger('role_id')->nullable();
    
                $table->foreign('ability_id')->references('id')->on('abilities')->onDelete('cascade');
                $table->foreign('role_id')->references('id')->on('roles')->onDelete('cascade');
            });
    
            Schema::create('abilities_users', function (Blueprint $table) {
                $table->id();
                $table->string('access')->nullable();
                $table->unsignedBigInteger('user_id')->nullable();
                $table->unsignedBigInteger('ability_id')->nullable();
    
                $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
                $table->foreign('ability_id')->references('id')->on('abilities')->onDelete('cascade');
            });

            $abilities = [
                ['module' => 'user', 'name' => 'manage'],
                ['module' => 'role', 'name' => 'manage'],
                ['module' => 'team', 'name' => 'manage'],
            ];

            foreach ($abilities as $ability) {
                DB::table('abilities')->insert(
                    array_merge($ability, ['created_at' => now(), 'updated_at' => now()])
                );
            }
        }
        else {
            Schema::dropIfExists('abilities_users');
            Schema::dropIfExists('abilities_roles');
            Schema::dropIfExists('abilities');
        }
    }

    /**
     * Create files table
     * 
     * @return void
     */
    public function createFilesTable($up = true)
    {
        if ($up) {
            Schema::create('files', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->string('mime')->nullable();
                $table->decimal('size', 20, 2)->nullable();
                $table->text('url')->nullable();
                $table->json('data')->nullable();
                $table->timestamps();
                $table->unsignedBigInteger('created_by')->nullable();

                $table->foreign('created_by')->references('id')->on('users')->onDelete('set null');
            });
        }
        else {
            Schema::dropIfExists('files');
        }
    }

    /**
     * Create labels table
     * 
     * @return void
     */
    public function createLabelsTable($up = true)
    {
        if ($up) {
            Schema::create('labels', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->string('slug')->nullable();
                $table->string('type')->nullable();
                $table->timestamps();
            });
        }
        else {
            Schema::dropIfExists('labels');
        }
    }

    /**
     * Create blogs table
     * 
     * @return void
     */
    public function createBlogsTable($up = true)
    {
        if ($up) {
            Schema::create('blogs', function (Blueprint $table) {
                $table->id();
                $table->string('title');
                $table->string('slug')->nullable();
                $table->string('redirect_slug')->nullable();
                $table->longText('content')->nullable();
                $table->json('seo')->nullable();
                $table->unsignedBigInteger('cover_id')->nullable();
                $table->timestamp('published_at')->nullable();
                $table->timestamps();
                $table->unsignedBigInteger('created_by')->nullable();

                $table->foreign('cover_id')->references('id')->on('files')->onDelete('set null');
                $table->foreign('created_by')->references('id')->on('users')->onDelete('set null');
            });

            Schema::create('blogs_labels', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('blog_id');
                $table->unsignedBigInteger('label_id');

                $table->foreign('blog_id')->references('id')->on('blogs')->onDelete('cascade');
                $table->foreign('label_id')->references('id')->on('labels')->onDelete('cascade');
            });
        }
        else {
            Schema::dropIfExists('blogs');
        }
    }

    /**
     * Create pages table
     * 
     * @return void
     */
    public function createPagesTable($up = true)
    {
        if ($up) {
            Schema::create('pages', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->string('title')->nullable();
                $table->string('slug')->nullable();
                $table->longText('content')->nullable();
                $table->json('seo')->nullable();
                $table->json('data')->nullable();
                $table->timestamps();
            });

            DB::table('pages')->insert([
                [
                    'name' => 'Privacy',
                    'title' => 'Privacy Policy',
                    'slug' => 'privacy',
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'name' => 'Terms',
                    'title' => 'Terms and Conditions',
                    'slug' => 'terms',
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
            ]);
        }
        else {
            Schema::dropIfExists('pages');
        }
    }

    /**
     * Create enquiries table
     * 
     * @return void
     */
    public function createEnquiriesTable($up = true)
    {
        if ($up) {
            Schema::create('enquiries', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->string('phone')->nullable();
                $table->string('email')->nullable();
                $table->longText('message')->nullable();
                $table->longText('remark')->nullable();
                $table->string('status')->nullable();
                $table->timestamps();
            });
        }
        else {
            Schema::dropIfExists('enquiries');
        }
    }

    /**
     * Create site settings table
     * 
     * @return void
     */
    public function createSiteSettingsTable($up = true)
    {
        if ($up) {
            Schema::create('site_settings', function (Blueprint $table) {
                $table->id();
                $table->string('name')->unique();
                $table->longText('value')->nullable();
            });

            DB::table('site_settings')->insert([
                ['name' => 'company', 'value' => null],
                ['name' => 'phone', 'value' => null],
                ['name' => 'email', 'value' => null],
                ['name' => 'whatsapp', 'value' => null],
                ['name' => 'address', 'value' => null],
                ['name' => 'facebook', 'value' => null],
                ['name' => 'instagram', 'value' => null],
                ['name' => 'twitter', 'value' => null],
                ['name' => 'linkedin', 'value' => null],
                ['name' => 'seo_title', 'value' => null],
                ['name' => 'seo_description', 'value' => null],
                ['name' => 'seo_image', 'value' => null],
                ['name' => 'ga_id', 'value' => null],
                ['name' => 'gtm_id', 'value' => null],
                ['name' => 'fbpixel_id', 'value' => null],
                ['name' => 'mailer', 'value' => 'smtp'],
                ['name' => 'smtp_host', 'value' => 'smtp.mailtrap.io'],
                ['name' => 'smtp_port', 'value' => '2525'],
                ['name' => 'smtp_username', 'value' => '1f6300b6d6e996'],
                ['name' => 'smtp_password', 'value' => '33647bb47622ee'],
                ['name' => 'smtp_encryption', 'value' => 'tls'],
                ['name' => 'mailgun_domain', 'value' => null],
                ['name' => 'mailgun_secret', 'value' => null],
                ['name' => 'notify_from', 'value' => null],
                ['name' => 'notify_to', 'value' => null],
                ['name' => 'filesystem', 'value' => 'local'],
                ['name' => 'do_spaces_key', 'value' => null],
                ['name' => 'do_spaces_secret', 'value' => null],
                ['name' => 'do_spaces_region', 'value' => 'sgp1'],
                ['name' => 'do_spaces_bucket', 'value' => null],
                ['name' => 'do_spaces_endpoint', 'value' => null],
                ['name' => 'do_spaces_cdn', 'value' => null],
            ]);
        }
        else {
            Schema::dropIfExists('site_settings');
        }
    }

    /**
     * Create root user login
     * 
     * @return void
     */
    public function createRootUser($up = true)
    {
        if ($up) {
            DB::table('users')->insert([
                'name' => 'Root',
                'email' => 'root@jiannius.com',
                'password' => bcrypt('password'),
                'status' => 'active',
                'role_id' => 1,
                'email_verified_at' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
        else {
            DB::table('users')->where('email', 'root@jiannius.com')->delete();
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $this->createRootUser(false);
        $this->createSiteSettingsTable(false);
        $this->createEnquiriesTable(false);
        $this->createPagesTable(false);
        $this->createBlogsTable(false);
        $this->createLabelsTable(false);
        $this->createFilesTable(false);
        $this->createAbilitiesTables(false);
        $this->createRolesTables(false);
        $this->createTeamsTables(false);
        $this->modifyUsersTable(false);
    }
}