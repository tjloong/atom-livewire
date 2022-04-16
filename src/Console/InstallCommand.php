<?php

namespace Jiannius\Atom\Console;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use App\Models\User;

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
            '@tailwindcss/forms' => '^0.5',
            '@tailwindcss/typography' => '^0.5',
            'postcss' => '^8.4.5',
            'postcss-import' => '^14.0.2',
            'postcss-nesting' => '^10.1.2',
        ],
        'dependencies' => [
            'alpinejs' => '^3.4.2',
            'dayjs' => '^1.10.7',
            'flatpickr' => '^4.6.9',
            'tailwindcss' => '^3',
        ],
    ];

    protected $modules = [
        'base',
        'roles',
        'permissions',
        'taxes',
        'pages',
        'teams',
        'blogs',
        'enquiries',
        'ticketing',
        'plans',
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
     * Install plans
     */
    private function installPlans()
    {
        $this->newLine();
        $this->info('Installing plans module...');

        $this->installTaxes();

        if (Schema::hasTable('plans')) $this->warn('plans table exists, skipped.');
        else {
            Schema::create('plans', function($table) {
                $table->id();
                $table->string('name');
                $table->string('slug');
                $table->unsignedInteger('trial')->nullable();
                $table->string('payment_description')->nullable();
                $table->text('excerpt')->nullable();
                $table->json('features')->nullable();
                $table->string('cta')->nullable();
                $table->boolean('is_active')->nullable();
                $table->timestamps();
                $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('set null');
            });

            $this->line('plans table created successfully.');
        }
    
        if (Schema::hasTable('plan_prices')) $this->warn('plan_prices table exists, skipped.');
        else {
            Schema::create('plan_prices', function ($table) {
                $table->id();
                $table->string('currency')->nullable();
                $table->decimal('amount', 20, 2)->nullable();
                $table->decimal('discount', 20, 2)->nullable();
                $table->string('country')->nullable();
                $table->string('shoutout')->nullable();
                $table->string('expired_after')->nullable();
                $table->boolean('is_lifetime')->nullable();
                $table->boolean('is_default')->nullable();
                $table->string('stripe_price_id')->nullable();
                $table->foreignId('tax_id')->nullable()->constrained()->onDelete('set null');
                $table->foreignId('plan_id')->constrained()->onDelete('cascade');
                $table->timestamps();
                $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('set null');
            });

            $this->line('plan_prices table created successfully.');
        }
    
        if (Schema::hasTable('plan_upgradables')) $this->warn('plan_upgradables table exists, skipped');
        else {
            Schema::create('plan_upgradables', function($table) {
                $table->id();
                $table->foreignId('plan_id')->constrained()->onDelete('cascade');
                $table->foreignId('upgradable_id')->constrained('plans')->onDelete('cascade');
            });
        }

        if (Schema::hasTable('plan_downgradables')) $this->warn('plan_downgradables table exists, skipped');
        else {
            Schema::create('plan_downgradables', function($table) {
                $table->id();
                $table->foreignId('plan_id')->constrained()->onDelete('cascade');
                $table->foreignId('downgradable_id')->constrained('plans')->onDelete('cascade');
            });
        }

        if (Schema::hasTable('account_orders')) $this->warn('account_orders table exists, skipped.');
        else {
            Schema::create('account_orders', function($table) {
                $table->id();
                $table->string('number')->nullable()->unique();
                $table->string('currency')->nullable();
                $table->decimal('amount', 20, 2)->nullable();
                $table->foreignId('account_id')->constrained()->onDelete('cascade');
                $table->timestamps();
            });

            $this->line('account_orders table created successfully.');
        }

        if (Schema::hasTable('account_order_items')) $this->warn('account_order_items table exists, skipped.');
        else {
            Schema::create('account_order_items', function($table) {
                $table->id();
                $table->string('name')->nullable();
                $table->string('currency')->nullable();
                $table->decimal('amount', 20, 2)->nullable();
                $table->decimal('discounted_amount', 20, 2)->nullable();
                $table->decimal('grand_total', 20, 2)->nullable();
                $table->foreignId('account_order_id')->constrained()->onDelete('cascade');
                $table->foreignId('plan_price_id')->nullable()->constrained()->onDelete('set null');
                $table->timestamps();
            });

            $this->line('account_order_items table created successfully.');
        }

        if (Schema::hasTable('account_payments')) $this->warn('account_payments table exists, skipped.');
        else {
            Schema::create('account_payments', function($table) {
                $table->id();
                $table->string('number')->nullable()->unique();
                $table->string('currency')->nullable();
                $table->decimal('amount', 20, 2)->nullable();
                $table->string('status')->nullable();
                $table->json('data')->nullable();
                $table->foreignId('account_id')->constrained()->onDelete('cascade');
                $table->foreignId('account_order_id')->nullable()->constrained()->onDelete('set null');
                $table->timestamps();
            });

            $this->line('account_payments table created successfully.');
        }

        if (Schema::hasTable('account_subscriptions')) $this->warn('account_subscriptions table exists, skipped.');
        else {
            Schema::create('account_subscriptions', function($table) {
                $table->id();
                $table->boolean('is_trial')->nullable();
                $table->timestamp('start_at')->nullable();
                $table->timestamp('expired_at')->nullable();
                $table->foreignId('account_id')->constrained()->onDelete('cascade');
                $table->foreignId('account_order_item_id')->nullable()->constrained()->onDelete('set null');
                $table->foreignId('plan_price_id')->nullable()->constrained()->onDelete('set null');
                $table->timestamps();
            });

            $this->line('account_subscriptions table created successfully.');
        }

        if (DB::table('plans')->count()) $this->warn('There are data in plans table, skipped populating dummy data.');
        else {
            DB::table('plans')->insert(collect([
                [
                    'name' => 'Starter',
                    'excerpt' => 'Get started with your first few projects.',
                    'payment_description' => 'Atom Starter Plan',
                    'cta' => 'Get Started',
                ],
                [
                    'name' => 'Pro',
                    'excerpt' => 'For professionals who need extra support, controls and security.',
                    'payment_description' => 'Atom Pro Plan',
                    'cta' => 'Upgrade to Pro',
                ],
            ])->map(fn($val) => array_merge($val, [
                'slug' => str($val['name'])->slug(),
                'trial' => 14,
                'features' => json_encode([
                    'Lorem ipsum dolor sit amet 1',
                    'Lorem ipsum dolor sit amet 2',
                    'Lorem ipsum dolor sit amet 3',
                    'Lorem ipsum dolor sit amet 4',
                    'Lorem ipsum dolor sit amet 5',
                    'Lorem ipsum dolor sit amet 6',
                    'Lorem ipsum dolor sit amet 7',
                    'Lorem ipsum dolor sit amet 8',
                    'Lorem ipsum dolor sit amet 9',
                ]),
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]))->values()->all());

            DB::table('plan_prices')->insert(collect([
                [
                    'amount' => 50,
                    'expired_after' => '1 month',
                    'is_default' => true,
                    'plan_id' => 1,
                ],
                [
                    'amount' => 550,
                    'expired_after' => '1 year',
                    'shoutout' => 'Save 10%!',
                    'plan_id' => 1,
                ],
                [
                    'amount' => 99,
                    'expired_after' => '1 month',
                    'is_default' => true,
                    'plan_id' => 2,
                ],
                [
                    'amount' => 1100,
                    'expired_after' => '1 year',
                    'shoutout' => 'Save 20%!',
                    'plan_id' => 2,
                ],
            ])->map(fn($val) => array_merge($val, [
                'currency' => 'MYR',
                'country' => 'MY',
                'shoutout' => $val['shoutout'] ?? null,
                'is_default' => $val['is_default'] ?? false,
                'is_lifetime' => false,
                'created_at' => now(),
                'updated_at' => now(),
            ]))->values()->all());

            $this->line('Added dummy plans.');
        }
    }

    /**
     * Install ticketing
     */
    private function installTicketing()
    {
        $this->newLine();
        $this->info('Installing ticketing module...');

        if (Schema::hasTable('tickets')) $this->warn('tickets table exists, skipped.');
        else {
            Schema::create('tickets', function ($table) {
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

        if (Schema::hasTable('ticket_comments')) $this->warn('ticket_comments table exists, skipped.');
        else {
            Schema::create('ticket_comments', function($table) {
                $table->id();
                $table->text('body')->nullable();
                $table->foreignId('ticket_id')->nullable()->constrained()->onDelete('cascade');
                $table->timestamps();
                $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('cascade');
            });

            $this->line('ticket_comments table created successfully.');
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
                $table->unsignedBigInteger('cover_id')->nullable();
                $table->timestamp('published_at')->nullable();
                $table->timestamps();
                $table->unsignedBigInteger('created_by')->nullable();
    
                $table->foreign('cover_id')->references('id')->on('files')->onDelete('set null');
                $table->foreign('created_by')->references('id')->on('users')->onDelete('set null');
            });

            $this->line('blogs table created successfully.');
        }

        if (Schema::hasTable('blog_labels')) $this->warn('blog_labels table exists, skipped.');
        else {
            Schema::create('blog_labels', function ($table) {
                $table->id();
                $table->unsignedBigInteger('blog_id');
                $table->unsignedBigInteger('label_id');
    
                $table->foreign('blog_id')->references('id')->on('blogs')->onDelete('cascade');
                $table->foreign('label_id')->references('id')->on('labels')->onDelete('cascade');
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
            $this->line('pages table created successfully.');
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
            ['name' => 'gmap_url', 'value' => null],
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
                $table->string('region')->nullable();
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

        if (Schema::hasTable('users_permissions')) $this->warn('users_permissions table exists, skipped.');
        else {
            Schema::create('users_permissions', function($table) {
                $table->id();
                $table->string('permission');
                $table->boolean('is_granted')->nullable();
                $table->unsignedBigInteger('user_id')->nullable();

                $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            });

            $this->line('users_permissions table created successfully.');
        }

        if (Schema::hasTable('roles')) {
            if (Schema::hasTable('roles_permissions')) $this->warn('roles_permissions table exists, skipped.');
            else {
                Schema::create('roles_permissions', function ($table) {
                    $table->id();
                    $table->string('permission');
                    $table->boolean('is_granted')->nullable();
                    $table->unsignedBigInteger('role_id')->nullable();
        
                    $table->foreign('role_id')->references('id')->on('roles')->onDelete('cascade');
                });
    
                $this->line('roles_permissions table created successfully.');
            }
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
                $table->unsignedBigInteger('created_by')->nullable();
    
                $table->foreign('created_by')->references('id')->on('users')->onDelete('set null');
            });
            
            $this->line('roles table created successfully.');
        }

        if (Schema::hasColumn('users', 'role_id')) $this->warn('users table already has role_id column, skipped.');
        else {
            Schema::table('users', function ($table) {
                $table->unsignedBigInteger('role_id')->nullable()->after('is_active');
                $table->foreign('role_id')->references('id')->on('roles')->onDelete('set null');
            });
            $this->line('Added role_id column to users table.');
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

        foreach ([
            ['name' => 'modules', 'value' => null],
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
            ['name' => 'stripe_public_key', 'value' => null],
            ['name' => 'stripe_secret_key', 'value' => null],
            ['name' => 'stripe_webhook_key', 'value' => null],
            ['name' => 'gkash_mid', 'value' => null],
            ['name' => 'gkash_signature_key', 'value' => null],
            ['name' => 'gkash_sandbox_url', 'value' => 'https://api-staging.pay.asia/api/PaymentForm.aspx'],
            ['name' => 'gkash_url', 'value' => 'https://api.gkash.my/api/PaymentForm.aspx'],
            ['name' => 'ozopay_tid', 'value' => null],
            ['name' => 'ozopay_secret', 'value' => null],
            ['name' => 'ozopay_sandbox_url', 'value' => 'https://uatpayment.ozopay.com/PaymentEntry/PaymentOption'],
            ['name' => 'ozopay_url', 'value' => 'https://checkout.ozopay.com/Paymententry/PaymentOption'],
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
                $table->unsignedBigInteger('created_by')->nullable();
    
                $table->foreign('created_by')->references('id')->on('users')->onDelete('set null');
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
     * Install users
     */
    private function installUsers()
    {
        $this->newLine();
        $this->info('Installing users module...');

        Schema::table('users', function ($table) {
            $table->string('password')->nullable()->change();
        });
        $this->line('Made password column in users table nullable.');

        if (Schema::hasColumn('users', 'activated_at')) $this->warn('users table already has activated_at column, skipped.');
        else {
            Schema::table('users', function ($table) {
                $table->timestamp('activated_at')->nullable()->after('remember_token');
            });
            $this->line('Added activated_at column to users table.');
        }

        if (Schema::hasColumn('users', 'account_id')) $this->warn('users table already has account_id column, skipped.');
        else {
            Schema::table('users', function ($table) {
                $table->foreignId('account_id')->nullable()->after('remember_token');
                $table->foreign('account_id')->references('id')->on('accounts')->onDelete('cascade');
            });
            $this->line('Added account_id column to users table.');
        }

        if (Schema::hasColumn('users', 'visibility')) $this->warn('users table already has visibility column, skipped.');
        else {
            Schema::table('users', function ($table) {
                $table->string('visibility')->nullable()->after('remember_token');
            });
            $this->line('Added visibility column to users table.');
        }

        if (Schema::hasColumn('users', 'blocked_at')) $this->warn('users table already has blocked_at column, skipped.');
        else {
            Schema::table('users', function ($table) {
                $table->timestamp('blocked_at')->nullable();
            });
            $this->line('Added blocked_at column to users table.');
        }

        if (Schema::hasColumn('users', 'deleted_at')) $this->warn('users table already has deleted_at column, skipped.');
        else {
            Schema::table('users', function ($table) {
                $table->timestamp('deleted_at')->nullable();
            });
            $this->line('Added deleted_at column to users table.');
        }
        
        if (Schema::hasColumn('users', 'created_by')) $this->warn('users table already has created_by column, skipped.');
        else {
            Schema::table('users', function ($table) {
                $table->foreignId('created_by')->nullable();
                $table->foreign('created_by')->references('id')->on('users')->onDelete('set null');
            });
            $this->line('Added created_by column to users table.');
        }
        
        if (Schema::hasColumn('users', 'blocked_by')) $this->warn('users table already has blocked_by column, skipped.');
        else {
            Schema::table('users', function ($table) {
                $table->foreignId('blocked_by')->nullable();
                $table->foreign('blocked_by')->references('id')->on('users')->onDelete('set null');
            });
            $this->line('Added blocked_by column to users table.');
        }
        
        if (Schema::hasColumn('users', 'deleted_by')) $this->warn('users table already has deleted_by column, skipped.');
        else {
            Schema::table('users', function ($table) {
                $table->foreignId('deleted_by')->nullable();
                $table->foreign('deleted_by')->references('id')->on('users')->onDelete('set null');
            });
            $this->line('Added deleted_by column to users table.');
        }
        
        if (DB::table('users')->where('email', User::ROOT_EMAIL)->count()) $this->warn('Root user exists, skipped.');
        else {
            $accountId = DB::table('accounts')->insert([
                'type' => 'root',
                'name' => 'Root',
                'email' => User::ROOT_EMAIL,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            DB::table('users')->insert([
                'name' => 'Root',
                'email' => User::ROOT_EMAIL,
                'password' => bcrypt('password'),
                'visibility' => 'global',
                'account_id' => $accountId,
                'email_verified_at' => now(),
                'activated_at' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            $this->line('Added Root user.');
        }
    }

    /**
     * Install accounts
     */
    private function installAccounts()
    {
        $this->newLine();
        $this->info('Installing accounts module...');

        if (Schema::hasTable('accounts')) $this->warn('accounts table exists, skipped.');
        else {
            Schema::create('accounts', function($table) {
                $table->id();
                $table->string('type');
                $table->string('name')->nullable();
                $table->string('email')->nullable();
                $table->string('phone')->nullable();
                $table->json('data')->nullable();
                $table->boolean('agree_tnc')->nullable();
                $table->boolean('agree_marketing')->nullable();
                $table->timestamp('onboarded_at')->nullable();
                $table->timestamps();
                $table->timestamp('deleted_at')->nullable();
                $table->timestamp('blocked_at')->nullable();
                $table->foreignId('deleted_by')->nullable()->constrained('users')->onDelete('set null');
                $table->foreignId('blocked_by')->nullable()->constrained('users')->onDelete('set null');
            });

            $this->line('accounts table created successfully.');
        }

        if (Schema::hasTable('account_settings')) $this->warn('account_settings table exists, skipped.');
        else {
            Schema::create('account_settings', function($table) {
                $table->id();
                $table->string('timezone')->nullable();
                $table->string('locale')->nullable();
                $table->foreignId('account_id')->constrained()->onDelete('cascade');
                $table->timestamps();
            });

            $this->line('account_settings table created successfully.');
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

        $this->installAccounts();
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