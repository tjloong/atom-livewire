<?php

namespace Jiannius\Atom\Console;

use Illuminate\Console\Command;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Jiannius\Atom\AtomServiceProvider;
use App\Models\User;

class InstallCommand extends Command
{
    protected $signature = 'atom:install
                            {modules? : Modules to be installed. Separate multiple modules with comma.}
                            {--force : Force publishing.}
                            {--static : Install static site.}';

    protected $description = 'Install Atom and it\'s modules.';

    protected $module;

    protected $baseMigrationName = '0000_00_00_999999_install_atom_base';

    protected $node = [
        'devDependencies' => [
            'postcss' => '^8.4.5',
            'postcss-import' => '^14.0.2',
            'postcss-nesting' => '^10.1.2',
        ],
        'dependencies' => [
            '@tailwindcss/forms' => '^0.3.0',
            '@tailwindcss/typography' => '^0.3.0',
            'alpinejs' => '^3.4.2',
            'boxicons' => '^2.0.9',
            'dayjs' => '^1.10.7',
            'flatpickr' => '^4.6.9',
            'tailwindcss' => '^2',
        ],
    ];

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
        if ($this->option('static')) {
            $this->installStaticSite();
        }
        else if (!$this->hasRunMigrationBefore()) {
            $this->newLine();
            $this->error('You have not run any migration yet. Please run the migration for the first time before installing Atom.');
            $this->newLine();
            return;
        }
        else if (!$this->isBaseInstalled() && (
            !$this->argument('modules') ||
            !in_array('base', explode(',', $this->argument('modules')))
        )) {
            $this->newLine();

            if ($this->confirm('You must install the base first before other modules can be installed. Proceed?', true)) {
                $this->installBase();

                $this->newLine();
                $this->info('Base installation done!');
                $this->comment('Please run atom:install again to install modules.');
                $this->newLine();
            }
            else return;
        }
        else {
            $modules = $this->argument('modules')
                ? explode(',', $this->argument('modules'))
                : $this->choice('Please select modules to install', [
                    'all',
                    'base',
                    'abilities',
                    'labels',
                    'pages',
                    'teams',
                    'blogs',
                    'enquiries',
                    'tickets',
                ], null, null, true);

            if (in_array('all', $modules) || in_array('base', $modules)) $this->installBase();
            if (in_array('all', $modules) || in_array('abilities', $modules)) $this->installAbilities();
            if (in_array('all', $modules) || in_array('labels', $modules)) $this->installLabels();
            if (in_array('all', $modules) || in_array('pages', $modules)) $this->installPages();
            if (in_array('all', $modules) || in_array('teams', $modules)) $this->installTeams();
            if (in_array('all', $modules) || in_array('blogs', $modules)) $this->installBlogs();
            if (in_array('all', $modules) || in_array('enquiries', $modules)) $this->installEnquiries();
            if (in_array('all', $modules) || in_array('tickets', $modules)) $this->installTickets();

            $this->newLine();
            $this->info('All done!');
            $this->comment('Please execute "npm install && npm run dev" to build your assets.');
            $this->newLine();
        }
    }

    /**
     * Install tickets
     */
    private function installTickets()
    {
        $this->newLine();
        $this->info('Installing tickets...');
        $this->publish('atom-views-ticket');

        if (Schema::hasTable('tickets')) $this->warn('tickets table exists, skipped.');
        else {
            Schema::create('tickets', function ($table) {
                $table->id();
                $table->string('number')->unique();
                $table->string('subject')->nullable();
                $table->longText('description')->nullable();
                $table->string('status')->nullable();
                $table->timestamps();
                $table->unsignedBigInteger('created_by')->nullable();
    
                $table->foreign('created_by')->references('id')->on('users')->onDelete('cascade');
            });

            $this->line('tickets table created successfully.');
        }

        if (Schema::hasTable('tickets_comments')) $this->warn('tickets_comments table exists, skipped.');
        else {
            Schema::create('tickets_comments', function($table) {
                $table->id();
                $table->text('body')->nullable();
                $table->unsignedBigInteger('ticket_id')->nullable();
                $table->timestamps();
                $table->unsignedBigInteger('created_by')->nullable();

                $table->foreign('ticket_id')->references('id')->on('tickets')->onDelete('cascade');
                $table->foreign('created_by')->references('id')->on('users')->onDelete('cascade');
            });

            $this->line('tickets_comments table created successfully.');
        }
    }

    /**
     * Install enquiries
     */
    private function installEnquiries()
    {
        $this->newLine();
        $this->info('Installing enquiries...');
        $this->publish('atom-views-enquiry');

        if (Schema::hasTable('enquiries')) $this->warn('enquiries table exists, skipped.');
        else {
            Schema::create('enquiries', function ($table) {
                $table->id();
                $table->string('name');
                $table->string('phone')->nullable();
                $table->string('email')->nullable();
                $table->longText('message')->nullable();
                $table->longText('remark')->nullable();
                $table->json('data')->nullable();
                $table->string('status')->nullable();
                $table->timestamps();
            });

            $this->line('enquiries table created successfully.');
        }
    }

    /**
     * Install blogs
     */
    private function installBlogs()
    {
        $this->newLine();
        $this->info('Installing blogs...');
        $this->publish('atom-views-blog');

        if (!Schema::hasTable('labels')) $this->installLabels();

        if (Schema::hasTable('blogs')) $this->warn('blogs table exists, skipped.');
        else {
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

            $this->line('blogs table created successfully.');
        }

        if (Schema::hasTable('blogs_labels')) $this->warn('blogs_labels table exists, skipped.');
        else {
            Schema::create('blogs_labels', function ($table) {
                $table->id();
                $table->unsignedBigInteger('blog_id');
                $table->unsignedBigInteger('label_id');
    
                $table->foreign('blog_id')->references('id')->on('blogs')->onDelete('cascade');
                $table->foreign('label_id')->references('id')->on('labels')->onDelete('cascade');
            });

            $this->line('blogs_labels table created successfully.');
        }
    }

    /**
     * Install teams
     */
    private function installTeams()
    {
        $this->newLine();
        $this->info('Installing teams...');
        $this->publish('atom-views-team');

        if (Schema::hasTable('teams')) $this->warn('teams table exists, skipped.');
        else {
            Schema::create('teams', function ($table) {
                $table->id();
                $table->string('name')->nullable();
                $table->text('description')->nullable();
                $table->timestamps();
                $table->unsignedBigInteger('created_by')->nullable();
    
                $table->foreign('created_by')->references('id')->on('users')->onDelete('set null');
            });
            $this->line('teams table created successfully.');
        }

        if (Schema::hasTable('teams_users')) $this->warn('teams_users table exists, skipped.');
        else {
            Schema::create('teams_users', function ($table) {
                $table->id();
                $table->unsignedBigInteger('team_id')->nullable();
                $table->unsignedBigInteger('user_id')->nullable();
    
                $table->foreign('team_id')->references('id')->on('teams')->onDelete('cascade');
                $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            });
            $this->line('teams_users table created successfully.');
        }
    }

    /**
     * Install pages
     */
    private function installPages()
    {
        $this->newLine();
        $this->info('Installing pages...');
        $this->publish('atom-views-page');

        if (Schema::hasTable('pages')) $this->warn('pages table exists, skipped.');
        else {
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
            $this->line('pages table installed successfully.');
        }

        foreach ([
            ['name' => 'Privacy', 'title' => 'Privacy Policy', 'slug' => 'privacy'],
            ['name' => 'Terms', 'title' => 'Terms and Conditions', 'slug' => 'terms'],
        ] as $page) {
            if (DB::table('pages')->where('slug', $page['slug'])->count()) continue;

            DB::table('pages')->insert(array_merge($page, [
                'created_at' => now(),
                'updated_at' => now(),
            ]));
        }
        $this->line('Added default pages.');

        foreach ([
            ['name' => 'company', 'value' => null],
            ['name' => 'phone', 'value' => null],
            ['name' => 'email', 'value' => null],
            ['name' => 'address', 'value' => null],
            ['name' => 'briefs', 'value' => null],
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
        ] as $setting) {
            if (DB::table('site_settings')->where('name', $setting['name'])->count()) continue;
            DB::table('site_settings')->insert($setting);
        }
        $this->line('Added additional site settings.');
    }

    /**
     * Install labels
     */
    private function installLabels()
    {
        $this->newLine();
        $this->info('Installing labels...');
        $this->publish('atom-views-label');

        if (Schema::hasTable('labels')) $this->warn('labels table exists, skipped.');
        else {
            Schema::create('labels', function ($table) {
                $table->id();
                $table->string('name');
                $table->string('slug')->nullable();
                $table->string('type')->nullable();
                $table->integer('seq')->nullable();
                $table->json('data')->nullable();
                $table->timestamps();
            });
    
            $this->line('labels table installed successfully.');
        }
    }

    /**
     * Install abilities
     */
    private function installAbilities()
    {
        $this->newLine();
        $this->info('Installing abilities...');
        $this->publish('atom-views-ability');

        if (Schema::hasTable('abilities')) $this->warn('abilities table exists, skipped.');
        else {
            Schema::create('abilities', function ($table) {
                $table->id();
                $table->string('module')->nullable();
                $table->string('name')->nullable();
                $table->timestamps();
            });

            $this->line('abilities table installed successfully.');
        }

        if (Schema::hasTable('abilities_roles')) $this->warn('abilities_roles table exists, skipped.');
        else {
            Schema::create('abilities_roles', function ($table) {
                $table->id();
                $table->unsignedBigInteger('ability_id')->nullable();
                $table->unsignedBigInteger('role_id')->nullable();
    
                $table->foreign('ability_id')->references('id')->on('abilities')->onDelete('cascade');
                $table->foreign('role_id')->references('id')->on('roles')->onDelete('cascade');
            });

            $this->line('abilities_roles table installed successfully.');
        }

        if (Schema::hasTable('abilities_users')) $this->warn('abilities_users table exists, skipped.');
        else {
            Schema::create('abilities_users', function ($table) {
                $table->id();
                $table->string('access')->nullable();
                $table->unsignedBigInteger('user_id')->nullable();
                $table->unsignedBigInteger('ability_id')->nullable();
    
                $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
                $table->foreign('ability_id')->references('id')->on('abilities')->onDelete('cascade');
            });

            $this->line('abilities_users table installed successfully.');
        }

        foreach ([
            ['module' => 'user', 'name' => 'manage'],
        ] as $ability) {
            if (DB::table('abilities')->where('module', $ability['module'])->where('name', $ability['name'])->count()) continue;
            DB::table('abilities')->insert(array_merge($ability, [
                'created_at' => now(), 
                'updated_at' => now(),
            ]));
        }
        $this->line('Added default abilities.');
    }

    /**
     * Install site settings
     */
    private function installSiteSettings()
    {
        if (Schema::hasTable('site_settings')) $this->warn('site_settings table exists, skipped.');
        else {
            Schema::create('site_settings', function ($table) {
                $table->id();
                $table->string('name')->unique();
                $table->longText('value')->nullable();
            });

            $this->line('site_settings table installed successfully.');
        }

        foreach ([
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
            ['name' => 'gmap_api', 'value' => 'AIzaSyBpxS4r78UhtulgcnqZIZ3KEj2cgHF5wy8'],
        ] as $setting) {
            if (DB::table('site_settings')->where('name', $setting['name'])->count()) continue;
            DB::table('site_settings')->insert($setting);
        }

        $this->line('Added default site settings.');
    }

    /**
     * Install files
     */
    private function installFiles()
    {
        if (Schema::hasTable('files')) $this->warn('files table exists, skipped.');
        else {
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
    
            $this->line('files table created successfully.');
        }
    }

    /**
     * Install users
     */
    private function installUsers()
    {
        Schema::table('users', function ($table) {
            $table->string('password')->nullable()->change();
        });
        $this->line('users table password column is nullable now.');

        if (Schema::hasColumn('users', 'status')) $this->warn('users table already has status column, skipped.');
        else {
            Schema::table('users', function ($table) {
                $table->string('status')->nullable()->after('password');
            });
            $this->line('Added status column to users table.');
        }
        
        if (Schema::hasColumn('users', 'created_by')) $this->warn('users table already has created_by column, skipped.');
        else {
            Schema::table('users', function ($table) {
                $table->unsignedBigInteger('created_by')->nullable();
                $table->foreign('created_by')->references('id')->on('users')->onDelete('set null');
            });
            $this->line('Added created_by column to users table.');
        }
        
        if (Schema::hasColumn('users', 'role_id')) $this->warn('users table already has role_id column, skipped.');
        else {
            Schema::table('users', function ($table) {
                $table->unsignedBigInteger('role_id')->nullable()->after('remember_token');
                $table->foreign('role_id')->references('id')->on('roles')->onDelete('set null');
            });
            $this->line('Added role_id column to users table.');
        }

        if ($rootUser = DB::table('users')->where('email', User::ROOT_EMAIL)->first()) {
            if (!$rootUser->role_id) {
                DB::table('users')->where('email', User::ROOT_EMAIL)->update(['role_id' => 1]);
                $this->line('Updated Root user role_id.');
            }
            else $this->warn('Root user exists, skipped.');
        }
        else {
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
            $this->line('Added Root user.');
        }
    }

    /**
     * Install roles
     */
    private function installRoles()
    {
        if (Schema::hasTable('roles')) $this->warn('roles table exists, skipped.');
        else {
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
            
            $this->line('roles table created successfully.');
        }

        foreach ([
            ['slug' => 'root', 'scope' => 'root'],
            ['slug' => 'administrator', 'scope' => 'global'],
            ['slug' => 'restricted-user', 'scope' => 'restrict'],
        ] as $role) {
            if (DB::table('roles')->where('slug', $role['slug'])->where('is_system', true)->count()) continue;

            DB::table('roles')->insert(array_merge($role, [
                'name' => Str::headline($role['slug']), 
                'is_system' => true, 
                'created_at' => now(), 
                'updated_at' => now(),
            ]));
        }

        $this->line('Added default roles.');
    }

    /**
     * Install base
     */
    private function installBase()
    {
        DB::table('migrations')->where('migration', $this->baseMigrationName)->delete();

        $this->publish('atom-install');

        // base
        $this->newLine();
        $this->info('Base installation...');
        $this->installRoles();
        $this->installUsers();
        $this->installFiles();
        $this->installSiteSettings();
        $this->updateNodePackages();

        replace_in_file(
            'public const HOME = \'/home\';',
            'public const HOME = \'/\';',
            app_path('Providers/RouteServiceProvider.php')
        );
        $this->line('Updated HOME constant in RouteServiceProvider.php');

        if (!file_exists(public_path('storage'))) {
            $this->call('storage:link');
        }

        DB::table('migrations')->insert([
            'migration' => $this->baseMigrationName,
            'batch' => (DB::table('migrations')->max('batch') ?? 1) + 1,
        ]);
    }

    /**
     * Install static site
     */
    private function installStaticSite()
    {
        $this->call('vendor:publish', [
            '--provider' => AtomServiceProvider::class,
            '--tag' => 'atom-install-static',
            '--force' => $this->option('force'),
        ]);
    }

    /**
     * Check migration has run before
     */
    private function hasRunMigrationBefore()
    {
        return Schema::hasTable('migrations') && DB::table('migrations')->count();
    }

    /**
     * Check base is installed
     */
    private function isBaseInstalled()
    {
        return DB::table('migrations')->where('migration', $this->baseMigrationName)->count() > 0;
    }

    /**
     * Publishing files
     */
    private function publish($tag)
    {
        $this->call('vendor:publish', [
            '--provider' => AtomServiceProvider::class,
            '--tag' => $tag,
            '--force' => $this->option('force'),
        ]);
    }

    /**
     * Update node packages
     * 
     * @return void
     */
    public function updateNodePackages()
    {
        // dev dependencies
        $this->writePackageJsonFile(function ($packages) {
            return $this->node['devDependencies'] + $packages;
        });
        $this->line('Updated dev dependencies in package.json');

        // dependencies
        $this->writePackageJsonFile(function ($packages) {
            return $this->node['dependencies'] + $packages;
        }, false);
        $this->line('Updated dependencies in package.json');
    }

    /**
     * Update the "package.json" file.
     *
     * @param  callable  $callback
     * @param  bool  $dev
     * @return void
     */
    protected static function writePackageJsonFile(callable $callback, $dev = true)
    {
        if (! file_exists(base_path('package.json'))) {
            return;
        }

        $configurationKey = $dev ? 'devDependencies' : 'dependencies';

        $packages = json_decode(file_get_contents(base_path('package.json')), true);

        $packages[$configurationKey] = $callback(
            array_key_exists($configurationKey, $packages) ? $packages[$configurationKey] : [],
            $configurationKey
        );

        ksort($packages[$configurationKey]);

        file_put_contents(
            base_path('package.json'),
            json_encode($packages, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT).PHP_EOL
        );
    }
}