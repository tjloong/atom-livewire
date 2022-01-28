<?php

namespace Jiannius\Atom\Console;

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class FeaturesCommand extends Command
{
    protected $signature = 'atom:features {--force : Force migration and publishing}';
    protected $description = 'Enable atom features according to atom config file.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        if (config('atom.static_site')) {
            $this->error('You have configure Atom as static site. Features only applicable to non-static site.');
            return;
        }
        
        $this->migrate();

        // base tables
        $this->rolesMigration();
        $this->usersMigration();
        $this->filesMigration();
        $this->siteSettingsMigration();

        collect(array_keys(config('atom.features')))
            ->reject(fn($name) => in_array($name, ['auth', 'roles', 'site_settings']))
            ->each(function($name) {
                $this->info('Configuring ' . Str::title($name) . ' feature...');
                
                $method = Str::camel('toggle-' . $name . '-feature');
                if (method_exists($this, $method)) $this->$method();
    
                $this->newLine();
            });
    }

    /**
     * Run initial migration
     * 
     * @return void
     */
    protected function migrate()
    {
        if (!Schema::hasTable('migrations') || !DB::table('migrations')->count()) {
            $this->call('migrate');
        }
    }

    /**
     * Roles migration
     * 
     * @return void
     */
    protected function rolesMigration()
    {
        if (Schema::hasTable('roles')) return;

        Schema::create('roles', function ($table) {
            $table->id();
            $table->string('name')->nullable();
            $table->string('slug')->nullable();
            $table->string('scope')->nullable();
            $table->boolean('is_system')->nullable();
            $table->timestamps();
            $table->unsignedBigInteger('created_by')->nullable();

            $table->foreign('created_by')->references('id')->on('users')->onDelete('set null');
        });

        $roles = [
            ['slug' => 'root', 'scope' => 'root'],
            ['slug' => 'administrator', 'scope' => 'global'],
            ['slug' => 'restricted-user', 'scope' => 'restrict'],
        ];

        foreach ($roles as $role) {
            DB::table('roles')->insert(
                array_merge($role, ['name' => Str::headline($role['slug']), 'is_system' => true, 'created_at' => now(), 'updated_at' => now()])
            );
        }
    }

    /**
     * Users migration
     * 
     * @return void
     */
    protected function usersMigration()
    {
        $this->info('Running users migration...');

        Schema::table('users', function ($table) {
            $table->string('password')->nullable()->change();
        });

        if (!Schema::hasColumn('users', 'status')) {
            Schema::table('users', function ($table) {
                $table->string('status')->nullable()->after('password');
            });
        }
        
        if (!Schema::hasColumn('users', 'created_by')) {
            Schema::table('users', function ($table) {
                $table->unsignedBigInteger('created_by')->nullable();
                $table->foreign('created_by')->references('id')->on('users')->onDelete('set null');
            });
        }
        
        if (!Schema::hasColumn('users', 'role_id')) {
            Schema::table('users', function ($table) {
                $table->unsignedBigInteger('role_id')->nullable()->after('remember_token');
                $table->foreign('role_id')->references('id')->on('roles')->onDelete('set null');
            });
        }

        if (!DB::table('users')->where('email', User::ROOT_EMAIL)->count()) {
            DB::table('users')->insert([
                'name' => 'Root',
                'email' => User::ROOT_EMAIL,
                'password' => bcrypt('password'),
                'status' => 'active',
                'role_id' => 1,
                'email_verified_at' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
        else DB::table('users')->where('email', User::ROOT_EMAIL)->update(['role_id' => 1]);
    }

    /**
     * Files migration
     * 
     * @return void
     */
    protected function filesMigration()
    {
        if (Schema::hasTable('files')) return;

        $this->info('Running files migration...');

        Schema::create('files', function ($table) {
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


    /**
     * Site settings migration
     * 
     * @return void
     */
    protected function siteSettingsMigration()
    {
        $config = config('atom.features.site_settings');

        if (!$config) return;
        if (Schema::hasTable('site_settings')) return;

        $this->info('Running site_settings migration...');

        Schema::create('site_settings', function ($table) {
            $table->id();
            $table->string('name')->unique();
            $table->longText('value')->nullable();
        });

        $settings = [
            ['name' => 'mailer', 'value' => 'smtp'],
            ['name' => 'smtp_host', 'value' => 'smtp.mailtrap.io'],
            ['name' => 'smtp_port', 'value' => '2525'],
            ['name' => 'smtp_username', 'value' => '1f6300b6d6e996'],
            ['name' => 'smtp_password', 'value' => '33647bb47622ee'],
            ['name' => 'smtp_encryption', 'value' => 'tls'],
            ['name' => 'mailgun_domain', 'value' => null],
            ['name' => 'mailgun_secret', 'value' => null],
            ['name' => 'notify_from', 'value' => 'no-reply@atom.test'],
            ['name' => 'notify_to', 'value' => 'admin@atom.test'],
            ['name' => 'filesystem', 'value' => 'local'],
            ['name' => 'do_spaces_key', 'value' => null],
            ['name' => 'do_spaces_secret', 'value' => null],
            ['name' => 'do_spaces_region', 'value' => 'sgp1'],
            ['name' => 'do_spaces_bucket', 'value' => null],
            ['name' => 'do_spaces_endpoint', 'value' => null],
            ['name' => 'do_spaces_cdn', 'value' => null],
        ];

        if ($config === 'cms') {
            $settings = array_merge($settings, [
                ['name' => 'company', 'value' => null],
                ['name' => 'phone', 'value' => null],
                ['name' => 'email', 'value' => null],
                ['name' => 'address', 'value' => null],
                ['name' => 'facebook', 'value' => null],
                ['name' => 'instagram', 'value' => null],
                ['name' => 'twitter', 'value' => null],
                ['name' => 'linkedin', 'value' => null],
                ['name' => 'youtube', 'value' => null],
                ['name' => 'spotify', 'value' => null],
                ['name' => 'tiktok', 'value' => null],
                ['name' => 'seo_title', 'value' => null],
                ['name' => 'seo_description', 'value' => null],
                ['name' => 'seo_image', 'value' => null],
                ['name' => 'ga_id', 'value' => null],
                ['name' => 'gtm_id', 'value' => null],
                ['name' => 'fbpixel_id', 'value' => null],
                ['name' => 'whatsapp', 'value' => null],
                ['name' => 'whatsapp_bubble', 'value' => false],
                ['name' => 'whatsapp_text', 'value' => null],
            ]);
        }

        DB::table('site_settings')->insert($settings);        
    }

    /**
     * Toggle labels feature
     * 
     * @return void
     */
    protected function toggleLabelsFeature()
    {
        $disable = function() {
            Schema::disableForeignKeyConstraints();
            Schema::dropIfExists('labels');
            Schema::enableForeignKeyConstraints();
        };

        // disable
        if (!enabled_feature('labels') && !enabled_feature('blogs')) {
            call_user_func($disable);
        }
        // enable
        else {
            if ($this->option('force')) call_user_func($disable);

            if (!Schema::hasTable('labels')) {
                Schema::create('labels', function ($table) {
                    $table->id();
                    $table->string('name');
                    $table->string('slug')->nullable();
                    $table->string('type')->nullable();
                    $table->integer('seq')->nullable();
                    $table->json('data')->nullable();
                    $table->timestamps();
                });
            }
        }
    }

    /**
     * Toggle pages feature
     * 
     * @return void
     */
    protected function togglePagesFeature()
    {
        // disable
        if (!enabled_feature('pages')) {
            Schema::dropIfExists('pages');
        }
        // enable
        else {
            if ($this->option('force')) Schema::dropIfExists('pages');

            if (!Schema::hasTable('pages')) {
                Schema::create('pages', function ($table) {
                    $table->id();
                    $table->string('name');
                    $table->string('title')->nullable();
                    $table->string('slug')->nullable();
                    $table->longText('content')->nullable();
                    $table->json('seo')->nullable();
                    $table->json('data')->nullable();
                    $table->timestamps();
                });

                $pages = [
                    ['name' => 'Privacy', 'title' => 'Privacy Policy', 'slug' => 'privacy'],
                    ['name' => 'Terms', 'title' => 'Terms and Conditions', 'slug' => 'terms'],
                ];
    
                foreach ($pages as $page) {
                    DB::table('pages')->insert(array_merge($page, [
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]));
                }
            }
        }
    }

    /**
     * Toggle abilities feature
     * 
     * @return void
     */
    protected function toggleAbilitiesFeature()
    {
        $disable = function() {
            Schema::dropIfExists('abilities_users');
            Schema::dropIfExists('abilities_roles');
            Schema::dropIfExists('abilities');
        };

        // disable
        if (!enabled_feature('abilities')) {
            call_user_func($disable);
        }
        // enable
        else {
            if ($this->option('force')) call_user_func($disable);

            if (!Schema::hasTable('abilities')) {
                Schema::create('abilities', function ($table) {
                    $table->id();
                    $table->string('module')->nullable();
                    $table->string('name')->nullable();
                    $table->timestamps();
                });
        
                Schema::create('abilities_roles', function ($table) {
                    $table->id();
                    $table->unsignedBigInteger('ability_id')->nullable();
                    $table->unsignedBigInteger('role_id')->nullable();
        
                    $table->foreign('ability_id')->references('id')->on('abilities')->onDelete('cascade');
                    $table->foreign('role_id')->references('id')->on('roles')->onDelete('cascade');
                });
        
                Schema::create('abilities_users', function ($table) {
                    $table->id();
                    $table->string('access')->nullable();
                    $table->unsignedBigInteger('user_id')->nullable();
                    $table->unsignedBigInteger('ability_id')->nullable();
        
                    $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
                    $table->foreign('ability_id')->references('id')->on('abilities')->onDelete('cascade');
                });

                DB::table('abilities')->insert([
                    ['module' => 'user', 'name' => 'manage', 'created_at' => now(), 'updated_at' => now()],
                ]);
            }
        }
    }

    /**
     * Toggle teams feature
     * 
     * @return void
     */
    protected function toggleTeamsFeature()
    {
        $disable = function() {
            Schema::dropIfExists('teams_users');
            Schema::dropIfExists('teams');
        };

        // disable
        if (!enabled_feature('teams')) {
            call_user_func($disable);
        }
        // enable
        else {
            if ($this->option('force')) call_user_func($disable);

            if (!Schema::hasTable('teams')) {
                Schema::create('teams', function ($table) {
                    $table->id();
                    $table->string('name')->nullable();
                    $table->text('description')->nullable();
                    $table->timestamps();
                    $table->unsignedBigInteger('created_by')->nullable();
        
                    $table->foreign('created_by')->references('id')->on('users')->onDelete('set null');
                });
        
                Schema::create('teams_users', function ($table) {
                    $table->id();
                    $table->unsignedBigInteger('team_id')->nullable();
                    $table->unsignedBigInteger('user_id')->nullable();
        
                    $table->foreign('team_id')->references('id')->on('teams')->onDelete('cascade');
                    $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
                });
            }
        }
    }

    /**
     * Toggle blogs feature
     * 
     * @return void
     */
    protected function toggleBlogsFeature()
    {
        $disable = function() {
            Schema::dropIfExists('blogs_labels');
            Schema::dropIfExists('blogs');
        };
        
        // disable
        if (!enabled_feature('blogs')) {
            call_user_func($disable);
        }
        // enable
        else {
            if ($this->option('force')) call_user_func($disable);

            if (!Schema::hasTable('blogs')) {
                Schema::create('blogs', function ($table) {
                    $table->id();
                    $table->string('title');
                    $table->string('slug')->nullable();
                    $table->string('redirect_slug')->nullable();
                    $table->text('excerpt')->nullable();
                    $table->longText('content')->nullable();
                    $table->json('seo')->nullable();
                    $table->unsignedBigInteger('cover_id')->nullable();
                    $table->timestamp('published_at')->nullable();
                    $table->timestamps();
                    $table->unsignedBigInteger('created_by')->nullable();
        
                    $table->foreign('cover_id')->references('id')->on('files')->onDelete('set null');
                    $table->foreign('created_by')->references('id')->on('users')->onDelete('set null');
                });
        
                Schema::create('blogs_labels', function ($table) {
                    $table->id();
                    $table->unsignedBigInteger('blog_id');
                    $table->unsignedBigInteger('label_id');
        
                    $table->foreign('blog_id')->references('id')->on('blogs')->onDelete('cascade');
                    $table->foreign('label_id')->references('id')->on('labels')->onDelete('cascade');
                });
            }
        }
    }

    /**
     * Toggle enquiries feature
     * 
     * @return void
     */
    protected function toggleEnquiriesFeature()
    {
        // disable
        if (!enabled_feature('enquiries')) {
            Schema::dropIfExists('enquiries');
        }
        // enable
        else {
            if ($this->option('force')) Schema::dropIfExists('enquiries');

            if (!Schema::hasTable('enquiries')) {
                Schema::create('enquiries', function ($table) {
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
        }
    }

    /**
     * Toggle messenger feature
     * 
     * @return void
     */
    protected function toggleMessengerFeature()
    {
        $disable = function() {
            Schema::dropIfExists('messenger_participants');
            Schema::dropIfExists('messenger_messages');
            Schema::dropIfExists('messenger_threads');
        };

        // disable
        if (!enabled_feature('messenger')) {
            call_user_func($disable);
        }
        else {
            if ($this->option('force')) call_user_func($disable);

            if (!Schema::hasTable('messenger_threads')) {
                Schema::create('messenger_threads', function ($table) {
                    $table->id();
                    $table->string('subject')->nullable();
                    $table->unsignedBigInteger('user_id')->nullable();
                    $table->boolean('is_archived')->nullable();
                    $table->timestamps();
        
                    $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
                });
        
                Schema::create('messenger_messages', function($table) {
                    $table->id();
                    $table->text('body')->nullable();
                    $table->unsignedBigInteger('messenger_thread_id')->nullable();
                    $table->unsignedBigInteger('user_id')->nullable();
                    $table->timestamps();
        
                    $table->foreign('messenger_thread_id')->references('id')->on('messenger_threads')->onDelete('cascade');
                    $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
                });

                Schema::create('messenger_participants', function($table) {
                    $table->id();
                    $table->unsignedBigInteger('messenger_thread_id')->nullable();
                    $table->unsignedBigInteger('user_id')->nullable();
                    $table->timestamps();

                    $table->foreign('messenger_thread_id')->references('id')->on('messenger_threads')->onDelete('cascade');
                    $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
                });
            }
        }
    }
}