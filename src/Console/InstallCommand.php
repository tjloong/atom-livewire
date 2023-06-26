<?php

namespace Jiannius\Atom\Console;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;

class InstallCommand extends Command
{
    protected $signature = 'atom:install
                            {modules? : Modules to be installed. Separate multiple modules with comma.}
                            {--static : Install static site.}
                            {--force : Force publishing.}';

    protected $description = 'Install Atom and it\'s modules.';

    protected $baseMigrationName = '0000_00_00_999999_install_atom_base';

    protected $node = [
        'devDependencies' => [
            '@tailwindcss/typography' => '^0.5',
            '@ryangjchandler/alpine-hooks' => '^1',
            'alpinejs' => '^3',
            'autoprefixer' => '^10',
            'postcss' => '^8',
            'postcss-import' => '^14',
            'tailwindcss' => '^3',
        ],
        'dependencies' => [
            'flatpickr' => '^4',
        ],
    ];

    protected $modules = [
        'base',
        'invitations',
        'roles',
        'permissions',
        'taxes',
        'pages',
        'teams',
        'blogs',
        'enquiries',
        'tickets',
        'comments',
        'plans',
        'line_items',
        'emails',
        'shareables',
        'banners',
        'tenants',
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
        $this->call('cache:clear');

        if ($this->option('static')) {
            $this->installStatic();
        }
        else {
            if (!$this->hasRunMigrationBefore()) {
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
                $selected = $this->argument('modules')
                    ? explode(',', $this->argument('modules'))
                    : $this->choice('Please select modules to install', array_merge(['all'], $this->modules), null, null, true);

                foreach ($this->modules as $module) {
                    if (in_array('all', $selected) || in_array($module, $selected)) {
                        call_user_func([$this, str()->camel('install-'.$module)]);
                        if ($module !== 'base') $this->markModuleEnabled($module);
                    }
                }
    
                $this->newLine();
                $this->info('All done!');
                $this->comment('Please execute "npm install && npm run dev" to build your assets.');
                $this->newLine();
            }
        }
    }

    /**
     * Mark module enabled
     */
    private function markModuleEnabled($module)
    {
        $query = DB::table('site_settings')->where('name', 'modules');

        $enabled = collect(json_decode($query->first()->value));
        $enabled->push($module);

        $value = $enabled->unique()->values()->all();

        $query->update(['value' => json_encode($value)]);
    }

    /**
     * Install banners
     */
    private function installBanners()
    {
        $this->newLine();
        $this->info('Installing banners table...');

        if (Schema::hasTable('banners')) $this->warn('banners table exists, skipped.');
        else {
            Schema::create('banners', function (Blueprint $table) {
                $table->id();
                $table->string('type')->nullable();
                $table->string('name')->nullable();
                $table->string('slug')->nullable();
                $table->text('description')->nullable();
                $table->json('placement')->nullable();
                $table->string('url')->nullable();
                $table->integer('seq')->nullable();
                $table->boolean('is_active')->nullable();
                $table->foreignId('image_id')->nullable()->constrained('files')->onDelete('set null');
                $table->date('start_at')->nullable();
                $table->date('end_at')->nullable();
                $table->timestamps();
                $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('set null');
            });
        }
    }

    /**
     * Install tenants
     */
    private function installTenants()
    {
        $this->newLine();
        $this->info('Installing tenants table...');

        if (Schema::hasTable('tenants')) $this->warn('tenants table exists, skipped.');
        else {
            Schema::create('tenants', function(Blueprint $table) {
                $table->id();
                $table->string('name')->nullable();
                $table->string('email')->nullable();
                $table->string('phone')->nullable();
                $table->string('fax')->nullable();
                $table->string('website')->nullable();
                $table->string('brn')->nullable();
                $table->text('address_1')->nullable();
                $table->text('address_2')->nullable();
                $table->string('city')->nullable();
                $table->string('zip')->nullable();
                $table->string('state')->nullable();
                $table->string('country')->nullable();
                $table->foreignId('avatar_id')->nullable()->constrained('files')->onDelete('set null');
                $table->timestamps();
            });

            Schema::create('tenant_settings', function (Blueprint $table) {
                $table->id();
                $table->string('key')->indexed();
                $table->json('value')->nullable();
                $table->foreignId('tenant_id')->constrained()->onDelete('cascade');
            });

            Schema::create('user_tenants', function (Blueprint $table) {
                $table->id();
                $table->string('visibility')->nullable();
                $table->boolean('is_owner')->nullable();
                $table->boolean('is_preferred')->nullable();
                $table->foreignId('tenant_id')->constrained()->onDelete('cascade');
                $table->foreignId('user_id')->constrained()->onDelete('cascade');
            });
        }
    }

    /**
     * Install shareables
     */
    private function installShareables()
    {
        $this->newLine();
        $this->info('Installing shareables table...');

        if (Schema::hasTable('shareables')) $this->warn('shareables table exists, skipped.');
        else {
            Schema::create('shareables', function(Blueprint $table) {
                $table->id();
                $table->ulid();
                $table->integer('valid_for')->nullable();
                $table->json('data')->nullable();
                $table->boolean('is_enabled')->nullable();
                $table->string('parent_type')->nullable();
                $table->unsignedBigInteger('parent_id')->nullable();
                $table->timestamp('expired_at')->nullable();
                $table->timestamps();
            });

            $this->line('shareables table created successfully.');
        }
    }

    /**
     * Install line items
     */
    private function installLineItems()
    {
        $this->newLine();
        $this->info('Installing line_items table...');

        if (Schema::hasTable('line_items')) $this->warn('line_items table exists, skipped.');
        else {
            Schema::create('line_items', function (Blueprint $table) {
                $table->id();
                $table->string('name')->nullable();
                $table->string('variant_name')->nullable();
                $table->text('description')->nullable();
                $table->double('qty')->nullable();
                $table->double('amount')->nullable();
                $table->double('subtotal')->nullable();
                $table->double('discount_amount')->nullable();
                $table->double('tax_amount')->nullable();
                $table->double('grand_total')->nullable();
                $table->integer('seq')->nullable();
                $table->json('data')->nullable();
                $table->foreignId('image_id')->nullable()->constrained('files')->onDelete('set null');
                $table->timestamps();
            });

            $this->line('line_items table created successfully...');
        }

        if (Schema::hasTable('taxes')) {
            Schema::create('line_item_taxes', function (Blueprint $table) {
                $table->id();
                $table->double('amount')->nullable();
                $table->foreignId('line_item_id')->constrained()->onDelete('cascade');
                $table->foreignId('tax_id')->constrained()->onDelete('cascade');
            });

            $this->line('line_item_taxes table created successfully.');
        }
    }

    /**
     * Install emails
     */
    private function installEmails()
    {
        if (Schema::hasTable('emails')) $this->warn('emails table exists, skipped.');
        else {
            Schema::create('emails', function (Blueprint $table) {
                $table->id();
                $table->json('from')->nullable();
                $table->json('to')->nullable();
                $table->json('cc')->nullable();
                $table->json('bcc')->nullable();
                $table->string('subject')->nullable();
                $table->longText('body')->nullable();
                $table->json('data')->nullable();
                $table->timestamps();
                $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('set null');
            });

            $this->line('emails table created successfully.');
        }
    }

    /**
     * Install plans
     */
    private function installPlans()
    {
        $this->newLine();
        $this->info('Installing plans module...');

        if (Schema::hasTable('plans')) $this->warn('plans table exists, skipped.');
        else {
            Schema::create('plans', function(Blueprint $table) {
                $table->id();
                $table->string('code')->unique();
                $table->string('name')->nullable();
                $table->text('description')->nullable();
                $table->text('features')->nullable();
                $table->string('country')->nullable();
                $table->string('currency')->nullable();
                $table->integer('trial')->nullable();
                $table->boolean('is_unique_trial')->nullable();
                $table->boolean('is_active')->nullable();
                $table->timestamps();
                $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('set null');
            });
            $this->line('plans table created successfully.');
        }

        if (Schema::hasTable('plan_prices')) $this->warn('plan_prices table exists, skipped.');
        else {
            Schema::create('plan_prices', function(Blueprint $table) {
                $table->id();
                $table->string('code')->unique();
                $table->decimal('amount', 20, 2)->nullable();
                $table->string('description')->nullable();
                $table->json('valid')->nullable();
                $table->boolean('is_recurring')->nullable();
                $table->boolean('is_active')->nullable();
                $table->foreignId('plan_id')->constrained()->onDelete('cascade');
                $table->timestamps();
                $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('set null');
            });
            $this->line('plan_prices table created successfully');
        }

        if (Schema::hasTable('plan_upgrades')) $this->warn('plan_upgrades table exists, skipped.');
        else {
            Schema::create('plan_upgrades', function(Blueprint $table) {
                $table->id();
                $table->foreignId('plan_id')->nullable()->constrained()->onDelete('cascade');
                $table->foreignId('upgrade_id')->nullable()->constrained('plans')->onDelete('cascade');
            });
            $this->line('plan_upgrades table created successfully');
        }

        if (Schema::hasTable('plan_payments')) $this->warn('plan_payments table exists, skipped.');
        else {
            Schema::create('plan_payments', function(Blueprint $table) {
                $table->id();
                $table->string('number')->nullable()->unique();
                $table->string('currency')->nullable();
                $table->decimal('amount', 20, 2)->nullable();
                $table->string('mode')->nullable();
                $table->string('description')->nullable();
                $table->string('status')->nullable();
                $table->json('data')->nullable();
                $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null');
                $table->timestamps();
                $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('set null');
            });
            $this->line('plan_payments table created successfully.');
        }

        if (Schema::hasTable('plan_subscriptions')) $this->warn('plan_subscriptions table exists, skipped.');
        else {
            Schema::create('plan_subscriptions', function(Blueprint $table) {
                $table->id();
                $table->string('currency')->nullable();
                $table->decimal('amount', 20, 2)->nullable();
                $table->decimal('discounted_amount', 20, 2)->nullable();
                $table->integer('extension')->nullable();
                $table->json('data')->nullable();
                $table->boolean('is_trial')->nullable();
                $table->timestamp('start_at')->nullable();
                $table->timestamp('end_at')->nullable();
                $table->timestamp('terminated_at')->nullable();
                $table->timestamp('provisioned_at')->nullable();
                $table->foreignId('user_id')->nullable()->constrained()->onDelete('cascade');
                $table->foreignId('price_id')->nullable()->constrained('plan_prices')->onDelete('set null');
                $table->foreignId('payment_id')->nullable()->constrained('plan_payments')->onDelete('cascade');
                $table->timestamps();
            });

            $this->line('plan_subscriptions table created successfully.');
        }
    }

    /**
     * Install tickets
     */
    private function installTickets()
    {
        $this->newLine();
        $this->info('Installing tickets module...');

        if (Schema::hasTable('tickets')) $this->warn('tickets table exists, skipped.');
        else {
            Schema::create('tickets', function (Blueprint $table) {
                $table->id();
                $table->string('number')->unique();
                $table->string('subject')->nullable();
                $table->longText('description')->nullable();
                $table->string('status')->nullable();
                $table->timestamps();
                $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('cascade');
            });

            $this->line('tickets table created successfully.');
        }
    }

    /**
     * Install comments
     */
    private function installComments()
    {
        if (Schema::hasTable('comments')) $this->warn('comments table exists, skipped.');
        else {
            Schema::create('comments', function (Blueprint $table) {
                $table->id();
                $table->text('body')->nullable();
                $table->string('parent_type')->nullable();
                $table->unsignedBigInteger('parent_id')->nullable();
                $table->timestamp('read_at')->nullable();
                $table->timestamps();
                $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('cascade');
            });

            $this->line('comments table created successfully.');
        }
    }

    /**
     * Install enquiries
     */
    private function installEnquiries()
    {
        $this->newLine();
        $this->info('Installing enquiries...');

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
                $table->foreignId('cover_id')->nullable()->constrained('files')->onDelete('set null');
                $table->timestamp('published_at')->nullable();
                $table->timestamps();
                $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('set null');
            });

            $this->line('blogs table created successfully.');
        }

        if (Schema::hasTable('blog_labels')) $this->warn('blog_labels table exists, skipped.');
        else {
            Schema::create('blog_labels', function ($table) {
                $table->id();
                $table->foreignId('blog_id')->constrained('blogs')->onDelete('cascade');
                $table->foreignId('label_id')->constrained('labels')->onDelete('cascade');
            });

            $this->line('blog_labels table created successfully.');
        }
    }

    /**
     * Install teams
     */
    private function installTeams()
    {
        $this->newLine();
        $this->info('Installing teams...');

        if (Schema::hasTable('teams')) $this->warn('teams table exists, skipped.');
        else {
            Schema::create('teams', function ($table) {
                $table->id();
                $table->string('name')->nullable();
                $table->text('description')->nullable();
                $table->timestamps();
                $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('set null');
            });
            $this->line('teams table created successfully.');
        }

        if (Schema::hasTable('team_users')) $this->warn('team_users table exists, skipped.');
        else {
            Schema::create('team_users', function ($table) {
                $table->id();
                $table->foreignId('team_id')->nullable()->constrained('teams')->onDelete('cascade');
                $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('cascade');
            });
            $this->line('team_users table created successfully.');
        }
    }

    /**
     * Install pages
     */
    private function installPages()
    {
        $this->newLine();
        $this->info('Installing pages...');

        if (Schema::hasTable('pages')) $this->warn('pages table exists, skipped.');
        else {
            Schema::create('pages', function ($table) {
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
            $this->line('pages table created successfully.');
        }

        foreach ([
            ['name' => 'Privacy', 'title' => 'Privacy Policy', 'slug' => 'privacy'],
            ['name' => 'Terms', 'title' => 'Terms and Conditions', 'slug' => 'terms'],
        ] as $page) {
            if (DB::table('pages')->where('slug', $page['slug'])->count()) continue;

            DB::table('pages')->insert(array_merge($page, [
                'locale' => 'en',
                'created_at' => now(),
                'updated_at' => now(),
            ]));
        }
        $this->line('Added default pages.');
    }

    /**
     * Install taxes
     */
    private function installTaxes()
    {
        $this->newLine();
        $this->info('Installing taxes module...');

        if (Schema::hasTable('taxes')) $this->warn('taxes table exists, skipped.');
        else {
            Schema::create('taxes', function($table) {
                $table->id();
                $table->string('name');
                $table->decimal('rate', 20, 2)->nullable();
                $table->string('country')->nullable();
                $table->string('state')->nullable();
                $table->boolean('is_active')->nullable();
                $table->timestamps();
                $table->softDeletes();
                $table->foreignId('deleted_by')->nullable()->constrained('users')->onDelete('set null');
            });

            $this->line('taxes table created successfully.');
        }
    }

    /**
     * Install permissions
     */
    private function installPermissions()
    {
        $this->newLine();
        $this->info('Installing permissions...');

        if (Schema::hasTable('permissions')) $this->warn('permissions table exists, skipped.');
        else {
            Schema::create('permissions', function (Blueprint $table) {
                $table->id();
                $table->string('permission');
                $table->boolean('is_granted')->nullable();
                $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('cascade');
            });
        }
    }

    /**
     * Install roles
     */
    private function installRoles()
    {
        $this->newLine();
        $this->info('Installing roles...');

        if (Schema::hasTable('roles')) $this->warn('roles table exists, skipped.');
        else {
            Schema::create('roles', function ($table) {
                $table->id();
                $table->string('name')->nullable();
                $table->string('slug')->nullable();
                $table->timestamps();
                $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('set null');
            });
            
            $this->line('roles table created successfully.');
        }

        if (Schema::hasColumn('users', 'role_id')) $this->warn('users table already has role_id column, skipped.');
        else {
            Schema::table('users', function ($table) {
                $table->foreignId('role_id')->nullable()->after('visibility')->constrained('roles')->onDelete('set null');
            });
            $this->line('Added role_id column to users table.');
        }
    }

    /**
     * Install invitations
     */
    private function installInvitations()
    {
        $this->newLine();
        $this->info('Installing invitations module...');

        if (Schema::hasTable('invitations')) $this->warn('invitations table exists, skipped.');
        else {
            Schema::create('invitations', function (Blueprint $table) {
                $table->id();
                $table->string('email')->nullable();
                $table->json('data')->nullable();
                $table->datetime('accepted_at')->nullable();
                $table->datetime('declined_at')->nullable();
                $table->datetime('expired_at')->nullable();
                $table->timestamps();
                $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('cascade');
            });
        }
    }

    /**
     * Install site settings
     */
    private function installSiteSettings()
    {
        $this->newLine();
        $this->info('Installing site settings module...');

        if (Schema::hasTable('site_settings')) $this->warn('site_settings table exists, skipped.');
        else {
            Schema::create('site_settings', function ($table) {
                $table->id();
                $table->string('name')->unique();
                $table->longText('value')->nullable();
            });

            $this->line('site_settings table installed successfully.');
        }

        model('site_setting')->repair();
        $this->line('Added default site settings.');
    }

    /**
     * Install files
     */
    private function installFiles()
    {
        $this->newLine();
        $this->info('Installing files module...');

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
                $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('set null');
            });
    
            $this->line('files table created successfully.');
        }
    }

    /**
     * Install labels
     */
    private function installLabels()
    {
        $this->newLine();
        $this->info('Installing labels module...');

        if (Schema::hasTable('labels')) $this->warn('labels table exists, skipped.');
        else {
            Schema::create('labels', function ($table) {
                $table->id();
                $table->json('name')->nullable();
                $table->string('slug')->nullable();
                $table->string('type')->nullable();
                $table->integer('seq')->nullable();
                $table->json('data')->nullable();
                $table->foreignId('parent_id')->nullable()->constrained('labels')->onDelete('cascade');
                $table->timestamps();
            });
    
            $this->line('labels table installed successfully.');
        }
    }

    /**
     * Install users
     */
    private function installUsers()
    {
        $this->newLine();
        $this->info('Installing users module...');

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

        $this->line('users table installed successfully.');

        // create root user
        $rootEmail = 'root@jiannius.com';
        if (DB::table('users')->where('email', $rootEmail)->count()) $this->warn('Root user exists, skipped.');
        else {
            DB::table('users')->insert([
                'name' => 'Root',
                'email' => $rootEmail,
                'password' => bcrypt('password'),
                'visibility' => 'global',
                'status' => 'active',
                'is_root' => true,
                'email_verified_at' => now(),
                'activated_at' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            $this->line('Added Root user.');
        }
    }

    /**
     * Install base
     */
    private function installBase()
    {
        DB::table('migrations')->where('migration', $this->baseMigrationName)->delete();

        $this->newLine();
        $this->info('Base installation...');

        $this->call('atom:publish', [
            'modules' => 'base',
            '--force' => $this->option('force'),
        ]);

        $this->removeLaravelInstalltionFiles();
        $this->installUsers();
        $this->installLabels();
        $this->installFiles();
        $this->installSiteSettings();
        $this->updateNodePackages();
        $this->updateHomeRoute();
        $this->linkStorage();

        DB::table('migrations')->insert([
            'migration' => $this->baseMigrationName,
            'batch' => (DB::table('migrations')->max('batch') ?? 1) + 1,
        ]);
    }

    /**
     * Install static site
     */
    private function installStatic()
    {
        $this->call('atom:publish', [
            '--static' => true,
            '--force' => $this->option('force'),
        ]);

        $this->updateNodePackages();
        $this->updateHomeRoute();
        $this->linkStorage();
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
     * Update home route
     */
    private function updateHomeRoute()
    {
        replace_in_file(
            'public const HOME = \'/home\';',
            'public const HOME = \'/\';',
            app_path('Providers/RouteServiceProvider.php')
        );

        $this->line('Updated HOME constant in RouteServiceProvider.php');
    }

    /**
     * Link storage folder
     */
    private function linkStorage()
    {
        if (!file_exists(public_path('storage'))) {
            $this->call('storage:link');
        }
    }

    /**
     * Remove laravel installation files
     */
    private function removeLaravelInstalltionFiles()
    {
        if (!$this->option('force')) return;
        
        collect([
            base_path('resources/views/welcome.blade.php'),
            base_path('resources/js/bootstrap.js'),
        ])->each(function($file) {
            if (file_exists($file)) unlink($file);
        });

        $routes = base_path('routes/web.php');
        file_put_contents($routes, '<?php');
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