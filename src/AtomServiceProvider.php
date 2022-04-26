<?php

namespace Jiannius\Atom;

use Livewire\Livewire;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Route;
use Jiannius\Atom\Console\RemoveCommand;
use Jiannius\Atom\Console\InstallCommand;
use Jiannius\Atom\Console\PublishCommand;
use Jiannius\Atom\Http\Middleware\IsRole;
use Jiannius\Atom\Http\Middleware\PortalGuard;
use Jiannius\Atom\Http\Middleware\TrackReferer;

class AtomServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(__DIR__.'/../stubs/config/atom.php', 'atom');
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'atom');
        $this->loadTranslationsFrom(__DIR__.'/../lang', 'atom');

        require_once __DIR__.'/Helpers.php';
        
        $this->registerBlade();
        $this->registerComponents();
        $this->registerLivewires();
        $this->registerMiddlewares();
        $this->registerRoutes();
        $this->registerGates();
        $this->registerCommands();
        
        $this->registerStaticPublishing();
        $this->registerPublishing();

        model('site_setting')->configureSMTP();
    }

    /**
     * Register custom blade directive
     * 
     * @return void
     */
    public function registerBlade()
    {
        Blade::if('route', function($value) {
            return collect((array)$value)->contains(function($name) {
                return request()->route()->getName() === $name
                    || request()->path() === $name
                    || request()->is($name);
            });
        });

        Blade::if('notroute', function($value) {
            return !collect((array)$value)->contains(function($name) {
                return request()->route()->getName() === $name
                    || request()->path() === $name
                    || request()->is($name);
            });
        });

        Blade::if('module', function($value) {
            return enabled_module($value);
        });

        Blade::if('root', function() {
            return auth()->user()->isAccountType('root');
        });

        Blade::if('accounttype', function($value) {
            return auth()->user()->isAccountType($value);
        });

        Blade::if('notaccounttype', function($value) {
            return !auth()->user()->isAccountType($value);
        });
    }

    /**
     * Register components
     * 
     * @return void
     */
    public function registerComponents()
    {
        Blade::component('ga', 'Jiannius\\Atom\\Components\\Ga');
        Blade::component('gtm', 'Jiannius\\Atom\\Components\\Gtm');
        Blade::component('seo', 'Jiannius\\Atom\\Components\\Seo');
        Blade::component('box', 'Jiannius\\Atom\\Components\\Box');
        Blade::component('tabs', 'Jiannius\\Atom\\Components\\Tabs');
        Blade::component('card', 'Jiannius\\Atom\\Components\\Card');
        Blade::component('icon', 'Jiannius\\Atom\\Components\\Icon');
        Blade::component('logo', 'Jiannius\\Atom\\Components\\Logo');
        Blade::component('alert', 'Jiannius\\Atom\\Components\\Alert');
        Blade::component('badge', 'Jiannius\\Atom\\Components\\Badge');
        Blade::component('table', 'Jiannius\\Atom\\Components\\Table');
        Blade::component('image', 'Jiannius\\Atom\\Components\\Image');
        Blade::component('modal', 'Jiannius\\Atom\\Components\\Modal');
        Blade::component('loader', 'Jiannius\\Atom\\Components\\Loader');
        Blade::component('notify', 'Jiannius\\Atom\\Components\\Notify');
        Blade::component('button', 'Jiannius\\Atom\\Components\\Button');
        Blade::component('drawer', 'Jiannius\\Atom\\Components\\Drawer');
        Blade::component('fbpixel', 'Jiannius\\Atom\\Components\\FbPixel');
        Blade::component('sidenav', 'Jiannius\\Atom\\Components\\Sidenav');
        Blade::component('stat-box', 'Jiannius\\Atom\\Components\\StatBox');
        Blade::component('dropdown', 'Jiannius\\Atom\\Components\\Dropdown');
        Blade::component('checkbox', 'Jiannius\\Atom\\Components\\Checkbox');
        Blade::component('file-card', 'Jiannius\\Atom\\Components\\FileCard');
        Blade::component('back-button', 'Jiannius\\Atom\\Components\\BackButton');
        Blade::component('admin-panel', 'Jiannius\\Atom\\Components\\AdminPanel');
        Blade::component('empty-state', 'Jiannius\\Atom\\Components\\EmptyState');
        Blade::component('alpine-data', 'Jiannius\\Atom\\Components\\AlpineData');
        Blade::component('page-header', 'Jiannius\\Atom\\Components\\PageHeader');
        Blade::component('breadcrumbs', 'Jiannius\\Atom\\Components\\Breadcrumbs');
        Blade::component('payment-gateway', 'Jiannius\\Atom\\Components\\PaymentGateway');
        
        Blade::component('input.ic', 'Jiannius\\Atom\\Components\\Input\\Ic');
        Blade::component('input.seo', 'Jiannius\\Atom\\Components\\Input\\Seo');
        Blade::component('input.text', 'Jiannius\\Atom\\Components\\Input\\Text');
        Blade::component('input.tags', 'Jiannius\\Atom\\Components\\Input\\Tags');
        Blade::component('input.date', 'Jiannius\\Atom\\Components\\Input\\Date');
        Blade::component('input.file', 'Jiannius\\Atom\\Components\\Input\\File');
        Blade::component('input.slug', 'Jiannius\\Atom\\Components\\Input\\Slug');
        Blade::component('input.agree', 'Jiannius\\Atom\\Components\\Input\\Agree');
        Blade::component('input.email', 'Jiannius\\Atom\\Components\\Input\\Email');
        Blade::component('input.field', 'Jiannius\\Atom\\Components\\Input\\Field');
        Blade::component('input.image', 'Jiannius\\Atom\\Components\\Input\\Image');
        Blade::component('input.radio', 'Jiannius\\Atom\\Components\\Input\\Radio');
        Blade::component('input.title', 'Jiannius\\Atom\\Components\\Input\\Title');
        Blade::component('input.phone', 'Jiannius\\Atom\\Components\\Input\\Phone');
        Blade::component('input.state', 'Jiannius\\Atom\\Components\\Input\\State');
        Blade::component('input.amount', 'Jiannius\\Atom\\Components\\Input\\Amount');
        Blade::component('input.gender', 'Jiannius\\Atom\\Components\\Input\\Gender');
        Blade::component('input.number', 'Jiannius\\Atom\\Components\\Input\\Number');
        Blade::component('input.picker', 'Jiannius\\Atom\\Components\\Input\\Picker');
        Blade::component('input.search', 'Jiannius\\Atom\\Components\\Input\\Search');
        Blade::component('input.select', 'Jiannius\\Atom\\Components\\Input\\Select');
        Blade::component('input.country', 'Jiannius\\Atom\\Components\\Input\\Country');
        Blade::component('input.currency', 'Jiannius\\Atom\\Components\\Input\\Currency');
        Blade::component('input.password', 'Jiannius\\Atom\\Components\\Input\\Password');
        Blade::component('input.checkbox', 'Jiannius\\Atom\\Components\\Input\\Checkbox');
        Blade::component('input.textarea', 'Jiannius\\Atom\\Components\\Input\\Textarea');
        Blade::component('input.richtext', 'Jiannius\\Atom\\Components\\Input\\Richtext');
        Blade::component('input.sortable', 'Jiannius\\Atom\\Components\\Input\\Sortable');
        
        Blade::component('builder.faq', 'Jiannius\\Atom\\Components\\Builder\\Faq');
        Blade::component('builder.hero', 'Jiannius\\Atom\\Components\\Builder\\Hero');
        Blade::component('builder.share', 'Jiannius\\Atom\\Components\\Builder\\Share');
        Blade::component('builder.footer', 'Jiannius\\Atom\\Components\\Builder\\Footer');
        Blade::component('builder.slider', 'Jiannius\\Atom\\Components\\Builder\\Slider');
        Blade::component('builder.navbar', 'Jiannius\\Atom\\Components\\Builder\\Navbar');
        Blade::component('builder.pricing', 'Jiannius\\Atom\\Components\\Builder\\Pricing');
        Blade::component('builder.testimonial', 'Jiannius\\Atom\\Components\\Builder\\Testimonial');
    }

    /**
     * Register livewires
     * 
     * @return void
     */
    public function registerLivewires()
    {
        // web
        Livewire::component('atom.web.pages.index', 'Jiannius\\Atom\\Http\\Livewire\\Web\\Pages\\Index');
        Livewire::component('atom.web.pages.blog', 'Jiannius\\Atom\\Http\\Livewire\\Web\\Pages\\Blog');
        Livewire::component('atom.web.pages.contact.index', 'Jiannius\\Atom\\Http\\Livewire\\Web\\Pages\\Contact\\Index');
        Livewire::component('atom.web.pages.contact.thank-you', 'Jiannius\\Atom\\Http\\Livewire\\Web\\Pages\\Contact\\ThankYou');

        // auth portal
        Livewire::component('atom.auth.login', 'Jiannius\\Atom\\Http\\Livewire\\Auth\\Login');
        Livewire::component('atom.auth.register', 'Jiannius\\Atom\\Http\\Livewire\\Auth\\Register');
        Livewire::component('atom.auth.register-form', 'Jiannius\\Atom\\Http\\Livewire\\Auth\\RegisterForm');
        Livewire::component('atom.auth.reset-password', 'Jiannius\\Atom\\Http\\Livewire\\Auth\\ResetPassword');
        Livewire::component('atom.auth.forgot-password', 'Jiannius\\Atom\\Http\\Livewire\\Auth\\ForgotPassword');

        // user portal
        Livewire::component('atom.account', 'Jiannius\\Atom\\Http\\Livewire\\Account\\Index');
        Livewire::component('atom.account.authentication.index', 'Jiannius\\Atom\\Http\\Livewire\\Account\\Authentication\\Index');
        Livewire::component('atom.account.authentication.profile', 'Jiannius\\Atom\\Http\\Livewire\\Account\\Authentication\\Profile');
        Livewire::component('atom.account.authentication.password', 'Jiannius\\Atom\\Http\\Livewire\\Account\\Authentication\\Password');

        // onboarding portal
        Livewire::component('atom.onboarding', 'Jiannius\\Atom\\Http\\Livewire\\Onboarding\\Index');
        Livewire::component('atom.onboarding.profile', 'Jiannius\\Atom\\Http\\Livewire\\Onboarding\\Profile');

        // billing portal
        Livewire::component('atom.billing', 'Jiannius\\Atom\\Http\\Livewire\\Billing\\Index');
        Livewire::component('atom.billing.plans', 'Jiannius\\Atom\\Http\\Livewire\\Billing\\Plans');
        Livewire::component('atom.billing.checkout', 'Jiannius\\Atom\\Http\\Livewire\\Billing\\Checkout');

        // ticket portal
        Livewire::component('atom.ticketing.listing', 'Jiannius\\Atom\\Http\\Livewire\\Ticketing\\Listing');
        Livewire::component('atom.ticketing.create', 'Jiannius\\Atom\\Http\\Livewire\\Ticketing\\Create');
        Livewire::component('atom.ticketing.update', 'Jiannius\\Atom\\Http\\Livewire\\Ticketing\\Update');
        Livewire::component('atom.ticketing.comments', 'Jiannius\\Atom\\Http\\Livewire\\Ticketing\\Comments');

        // dashboard
        Livewire::component('atom.dashboard', 'Jiannius\\Atom\\Http\\Livewire\\App\\Dashboard');

        // blog
        Livewire::component('atom.app.blog.create', 'Jiannius\\Atom\\Http\\Livewire\\App\\Blog\\Create');
        Livewire::component('atom.app.blog.listing', 'Jiannius\\Atom\\Http\\Livewire\\App\\Blog\\Listing');
        Livewire::component('atom.app.blog.update', 'Jiannius\\Atom\\Http\\Livewire\\App\\Blog\\Update\\Index');
        Livewire::component('atom.app.blog.update.content', 'Jiannius\\Atom\\Http\\Livewire\\App\\Blog\\Update\\Content');
        Livewire::component('atom.app.blog.update.seo', 'Jiannius\\Atom\\Http\\Livewire\\App\\Blog\\Update\\Seo');
        Livewire::component('atom.app.blog.update.settings', 'Jiannius\\Atom\\Http\\Livewire\\App\\Blog\\Update\\Settings');

        // enquiry
        Livewire::component('atom.app.enquiry.listing', 'Jiannius\\Atom\\Http\\Livewire\\App\\Enquiry\\Listing');
        Livewire::component('atom.app.enquiry.update', 'Jiannius\\Atom\\Http\\Livewire\\App\\Enquiry\\Update');

        // page
        Livewire::component('atom.app.page.listing', 'Jiannius\\Atom\\Http\\Livewire\\App\\Page\\Listing');
        Livewire::component('atom.app.page.update', 'Jiannius\\Atom\\Http\\Livewire\\App\\Page\\Update\\Index');
        Livewire::component('atom.app.page.update.content', 'Jiannius\\Atom\\Http\\Livewire\\App\\Page\\Update\\Content');

        // plan
        Livewire::component('atom.plan.listing', 'Jiannius\\Atom\\Http\\Livewire\\App\\Plan\\Listing');
        Livewire::component('atom.plan.create', 'Jiannius\\Atom\\Http\\Livewire\\App\\Plan\\Create');
        Livewire::component('atom.plan.update', 'Jiannius\\Atom\\Http\\Livewire\\App\\Plan\\Update');
        Livewire::component('atom.plan.form', 'Jiannius\\Atom\\Http\\Livewire\\App\\Plan\\Form');

        // plan price
        Livewire::component('atom.plan-price.create', 'Jiannius\\Atom\\Http\\Livewire\\App\\PlanPrice\\Create');
        Livewire::component('atom.plan-price.update', 'Jiannius\\Atom\\Http\\Livewire\\App\\PlanPrice\\Update');
        Livewire::component('atom.plan-price.form', 'Jiannius\\Atom\\Http\\Livewire\\App\\PlanPrice\\Form');

        // user
        Livewire::component('atom.app.user.listing', 'Jiannius\\Atom\\Http\\Livewire\\App\\User\\Listing');
        Livewire::component('atom.app.user.create', 'Jiannius\\Atom\\Http\\Livewire\\App\\User\\Create');
        Livewire::component('atom.app.user.update', 'Jiannius\\Atom\\Http\\Livewire\\App\\User\\Update');
        Livewire::component('atom.app.user.form', 'Jiannius\\Atom\\Http\\Livewire\\App\\User\\Form');

        // role
        Livewire::component('atom.role.listing', 'Jiannius\\Atom\\Http\\Livewire\\App\\Role\\Listing');
        Livewire::component('atom.role.create', 'Jiannius\\Atom\\Http\\Livewire\\App\\Role\\Create');
        Livewire::component('atom.role.update', 'Jiannius\\Atom\\Http\\Livewire\\App\\Role\\Update');
        Livewire::component('atom.role.form', 'Jiannius\\Atom\\Http\\Livewire\\App\\Role\\Form');

        // permission
        Livewire::component('atom.permission.listing', 'Jiannius\\Atom\\Http\\Livewire\\App\\Permission\\Listing');

        // account
        Livewire::component('atom.app.account.listing', 'Jiannius\\Atom\\Http\\Livewire\\App\\Account\\Listing');
        Livewire::component('atom.app.account.update', 'Jiannius\\Atom\\Http\\Livewire\\App\\Account\\Update\\Index');
        Livewire::component('atom.app.account.update.overview', 'Jiannius\\Atom\\Http\\Livewire\\App\\Account\\Update\\Overview');

        // team
        Livewire::component('atom.team.listing', 'Jiannius\\Atom\\Http\\Livewire\\App\\Team\\Listing');
        Livewire::component('atom.team.create', 'Jiannius\\Atom\\Http\\Livewire\\App\\Team\\Create');
        Livewire::component('atom.team.update', 'Jiannius\\Atom\\Http\\Livewire\\App\\Team\\Update');
        Livewire::component('atom.team.form', 'Jiannius\\Atom\\Http\\Livewire\\App\\Team\\Form');

        // label
        Livewire::component('atom.label.listing', 'Jiannius\\Atom\\Http\\Livewire\\App\\Label\\Listing');
        Livewire::component('atom.label.create', 'Jiannius\\Atom\\Http\\Livewire\\App\\Label\\Create');
        Livewire::component('atom.label.update', 'Jiannius\\Atom\\Http\\Livewire\\App\\Label\\Update');
        Livewire::component('atom.label.form', 'Jiannius\\Atom\\Http\\Livewire\\App\\Label\\Form');

        // file
        Livewire::component('atom.app.file.form', 'Jiannius\\Atom\\Http\\Livewire\\App\\File\\Form');
        Livewire::component('atom.app.file.listing', 'Jiannius\\Atom\\Http\\Livewire\\App\\File\\Listing');
        Livewire::component('atom.app.file.uploader', 'Jiannius\\Atom\\Http\\Livewire\\App\\File\\Uploader\Index');
        Livewire::component('atom.app.file.uploader.device', 'Jiannius\\Atom\\Http\\Livewire\\App\\File\\Uploader\Device');
        Livewire::component('atom.app.file.uploader.web-image', 'Jiannius\\Atom\\Http\\Livewire\\App\\File\\Uploader\WebImage');
        Livewire::component('atom.app.file.uploader.youtube', 'Jiannius\\Atom\\Http\\Livewire\\App\\File\\Uploader\Youtube');
        Livewire::component('atom.app.file.uploader.library', 'Jiannius\\Atom\\Http\\Livewire\\App\\File\\Uploader\Library');

        $components = [
            // site settings
            'atom.app.site-settings.index' => 'App\SiteSettings\Index',
            'atom.app.site-settings.profile' => 'App\SiteSettings\Profile',
            'atom.app.site-settings.seo' => 'App\SiteSettings\Seo',
            'atom.app.site-settings.analytics' => 'App\SiteSettings\Analytics',
            'atom.app.site-settings.social-media' => 'App\SiteSettings\SocialMedia',
            'atom.app.site-settings.whatsapp' => 'App\SiteSettings\Whatsapp',
            'atom.app.site-settings.system.email' => 'App\SiteSettings\System\Email',
            'atom.app.site-settings.system.storage' => 'App\SiteSettings\System\Storage',
            'atom.app.site-settings.payment-gateway.stripe' => 'App\SiteSettings\PaymentGateway\Stripe',
            'atom.app.site-settings.payment-gateway.gkash' => 'App\SiteSettings\PaymentGateway\Gkash',
            'atom.app.site-settings.payment-gateway.ozopay' => 'App\SiteSettings\PaymentGateway\Ozopay',
            'atom.app.site-settings.payment-gateway.ipay' => 'App\SiteSettings\PaymentGateway\Ipay',
        ];

        foreach ($components as $name => $class) {
            Livewire::component($name, 'Jiannius\\Atom\\Http\\Livewire\\'.$class);
        }
    }

    /**
     * Register middlewares
     * 
     * @return void
     */
    public function registerMiddlewares()
    {
        $router = app('router');
        $router->aliasMiddleware('role', IsRole::class);
        $router->aliasMiddleware('referer', TrackReferer::class);
        $router->aliasMiddleware('portal-guard', PortalGuard::class);
    }

    /**
     * Register routes
     * 
     * @return void
     */
    public function registerRoutes()
    {
        Route::group(['middleware' => 'web'], function () {
            $this->loadRoutesFrom(__DIR__.'/../routes/web.php');
            $this->loadRoutesFrom(__DIR__.'/../routes/auth.php');
        });
    }

    /**
     * Register Gates
     * 
     * @return void
     */
    public function registerGates()
    {
        if (config('atom.static_site')) return;
        
        Gate::before(function ($user, $permission) {
            if (!enabled_module('permissions')) return true;
            
            [$module, $action] = explode('.', $permission);
            $isActionDefined = in_array($action, config('atom.app.permissions.'.$module) ?? []);

            if (!$isActionDefined) return true;
            if ($user->is_root) return true;

            if (enabled_module('roles')) {
                return $user->permissions()->granted($permission)->count() > 0 || (
                    !$user->permissions()->forbidden($permission)->count()
                    && $user->role
                    && $user->role->can($permission)
                );
            }
            else {
                return $user->permissions()->granted($permission)->count() > 0;
            }
        });
    }

    /**
     * Register publishing for static site
     * 
     * @return void
     */
    public function registerStaticPublishing()
    {
        if (!$this->app->runningInConsole()) return;

        $this->publishes([
            __DIR__.'/../stubs-static/config' => base_path('config'),
            __DIR__.'/../stubs-static/jsconfig.json' => base_path('jsconfig.json'),
            __DIR__.'/../stubs-static/tailwind.config.js' => base_path('tailwind.config.js'),
            __DIR__.'/../stubs-static/webpack.config.js' => base_path('webpack.config.js'),
            __DIR__.'/../stubs-static/webpack.mix.js' => base_path('webpack.mix.js'),
            __DIR__.'/../stubs-static/resources/views/layouts' => resource_path('views/layouts'),
            __DIR__.'/../stubs-static/resources/css' => resource_path('css'),
            __DIR__.'/../stubs-static/resources/js' => resource_path('js'),
            __DIR__.'/../resources/views/errors' => resource_path('views/errors'),
            __DIR__.'/../resources/views/vendor' => resource_path('views/vendor'),
        ], 'atom-install-static');
    }

    /**
     * Register publishing
     * 
     * @return void
     */
    public function registerPublishing()
    {
        if (!$this->app->runningInConsole()) return;

        $this->publishes([
            __DIR__.'/../stubs/config' => base_path('config'),
            __DIR__.'/../stubs/app/Models' => app_path('Models'),
            __DIR__.'/../stubs/jsconfig.json' => base_path('jsconfig.json'),
            __DIR__.'/../stubs/tailwind.config.js' => base_path('tailwind.config.js'),
            __DIR__.'/../stubs/webpack.config.js' => base_path('webpack.config.js'),
            __DIR__.'/../stubs/webpack.mix.js' => base_path('webpack.mix.js'),
            __DIR__.'/../stubs/resources/views/layouts' => resource_path('views/layouts'),
            __DIR__.'/../stubs/resources/css' => resource_path('css'),
            __DIR__.'/../stubs/resources/js' => resource_path('js'),
            __DIR__.'/../resources/views/errors' => resource_path('views/errors'),
            __DIR__.'/../resources/views/vendor' => resource_path('views/vendor'),
        ], 'atom-base');

        foreach ([
            'app/user', 
            'app/role', 
            'app/file', 
            'app/site-settings', 
            'app/label', 
            'app/page', 
            'app/permission', 
            'app/team', 
            'app/blog', 
            'app/enquiry', 
            'app/plan',
            'web',
            'auth',
            'account',
            'ticketing',
            'onboarding',
        ] as $module) {
            $this->publishes([
                __DIR__.'/../resources/views/'.$module => resource_path('views/vendor/atom/'.$module),
            ], 'atom-views-'.(str_replace('app/', 'app-', $module)));
        }
    }

    /**
     * Register commands
     * 
     * @return void
     */
    public function registerCommands()
    {
        if (!$this->app->runningInConsole()) return;

        $this->commands([
            InstallCommand::class,
            RemoveCommand::class,
            PublishCommand::class,
        ]);
    }
}