<?php

namespace Jiannius\Atom\Providers;

use Livewire\Livewire;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Route;
use Jiannius\Atom\Console\RemoveCommand;
use Jiannius\Atom\Console\InstallCommand;
use Jiannius\Atom\Console\PublishCommand;
use Jiannius\Atom\Http\Middleware\IsRole;
use Jiannius\Atom\Http\Middleware\Locale;
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
        $this->mergeConfigFrom(__DIR__.'/../../stubs/config/atom.php', 'atom');
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->loadViewsFrom(__DIR__.'/../../resources/views', 'atom');
        $this->loadTranslationsFrom(__DIR__.'/../../lang', 'atom');

        require_once __DIR__.'/../Helpers.php';
        
        $this->registerBlade();
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

        // user portal
        Livewire::component('atom.account', 'Jiannius\\Atom\\Http\\Livewire\\Account\\Index');
        Livewire::component('atom.account.authentication.index', 'Jiannius\\Atom\\Http\\Livewire\\Account\\Authentication\\Index');
        Livewire::component('atom.account.authentication.profile', 'Jiannius\\Atom\\Http\\Livewire\\Account\\Authentication\\Profile');
        Livewire::component('atom.account.authentication.password', 'Jiannius\\Atom\\Http\\Livewire\\Account\\Authentication\\Password');

        // onboarding portal
        Livewire::component('atom.onboarding', 'Jiannius\\Atom\\Http\\Livewire\\Onboarding\\Index');
        Livewire::component('atom.onboarding.profile', 'Jiannius\\Atom\\Http\\Livewire\\Onboarding\\Profile');

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

        // team
        Livewire::component('atom.team.listing', 'Jiannius\\Atom\\Http\\Livewire\\App\\Team\\Listing');
        Livewire::component('atom.team.create', 'Jiannius\\Atom\\Http\\Livewire\\App\\Team\\Create');
        Livewire::component('atom.team.update', 'Jiannius\\Atom\\Http\\Livewire\\App\\Team\\Update');
        Livewire::component('atom.team.form', 'Jiannius\\Atom\\Http\\Livewire\\App\\Team\\Form');

        // file
        Livewire::component('atom.app.file.form', 'Jiannius\\Atom\\Http\\Livewire\\App\\File\\Form');
        Livewire::component('atom.app.file.listing', 'Jiannius\\Atom\\Http\\Livewire\\App\\File\\Listing');
        Livewire::component('atom.app.file.uploader', 'Jiannius\\Atom\\Http\\Livewire\\App\\File\\Uploader\Index');
        Livewire::component('atom.app.file.uploader.device', 'Jiannius\\Atom\\Http\\Livewire\\App\\File\\Uploader\Device');
        Livewire::component('atom.app.file.uploader.web-image', 'Jiannius\\Atom\\Http\\Livewire\\App\\File\\Uploader\WebImage');
        Livewire::component('atom.app.file.uploader.youtube', 'Jiannius\\Atom\\Http\\Livewire\\App\\File\\Uploader\Youtube');
        Livewire::component('atom.app.file.uploader.library', 'Jiannius\\Atom\\Http\\Livewire\\App\\File\\Uploader\Library');

        $components = [
            // auth portal
            'atom.auth.login' => 'Auth\Login',
            'atom.auth.register' => 'Auth\Register',
            'atom.auth.register-form' => 'Auth\RegisterForm',
            'atom.auth.reset-password' => 'Auth\ResetPassword',
            'atom.auth.forgot-password' => 'Auth\ForgotPassword',

            // billing portal
            'atom.billing' => 'Billing\Index',
            'atom.billing.plans' => 'Billing\Plans',
            'atom.billing.checkout' => 'Billing\Checkout',

            // account
            'atom.app.account.listing' => 'App\Account\Listing',
            'atom.app.account.update' => 'App\Account\Update\Index',
            'atom.app.account.update.overview' => 'App\Account\Update\Overview',

            // account payments
            'atom.app.account-payment.listing' => 'App\AccountPayment\Listing',
            'atom.app.account-payment.update' => 'App\AccountPayment\Update',

            // label
            'atom.app.label.listing' => 'App\Label\Listing',
            'atom.app.label.create' => 'App\Label\Create',
            'atom.app.label.update' => 'App\Label\Update',
            'atom.app.label.form' => 'App\Label\Form',

            // site settings
            'atom.app.site-settings.index' => 'App\SiteSettings\Index',
            'atom.app.site-settings.profile' => 'App\SiteSettings\Profile',
            'atom.app.site-settings.seo' => 'App\SiteSettings\Seo',
            'atom.app.site-settings.analytics' => 'App\SiteSettings\Analytics',
            'atom.app.site-settings.social-media' => 'App\SiteSettings\SocialMedia',
            'atom.app.site-settings.announcements' => 'App\SiteSettings\Announcements',
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
        $router->aliasMiddleware('locale', Locale::class);
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
            $this->loadRoutesFrom(__DIR__.'/../../routes/web.php');
            $this->loadRoutesFrom(__DIR__.'/../../routes/auth.php');
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
            __DIR__.'/../../stubs-static/config' => base_path('config'),
            __DIR__.'/../../stubs-static/jsconfig.json' => base_path('jsconfig.json'),
            __DIR__.'/../../stubs-static/tailwind.config.js' => base_path('tailwind.config.js'),
            __DIR__.'/../../stubs-static/webpack.config.js' => base_path('webpack.config.js'),
            __DIR__.'/../../stubs-static/webpack.mix.js' => base_path('webpack.mix.js'),
            __DIR__.'/../../stubs-static/resources/views/layouts' => resource_path('views/layouts'),
            __DIR__.'/../../stubs-static/resources/css' => resource_path('css'),
            __DIR__.'/../../stubs-static/resources/js' => resource_path('js'),
            __DIR__.'/../../resources/views/errors' => resource_path('views/errors'),
            __DIR__.'/../../resources/views/vendor' => resource_path('views/vendor'),
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
            __DIR__.'/../../stubs/config' => base_path('config'),
            __DIR__.'/../../stubs/app/Models' => app_path('Models'),
            __DIR__.'/../../stubs/jsconfig.json' => base_path('jsconfig.json'),
            __DIR__.'/../../stubs/tailwind.config.js' => base_path('tailwind.config.js'),
            __DIR__.'/../../stubs/webpack.config.js' => base_path('webpack.config.js'),
            __DIR__.'/../../stubs/webpack.mix.js' => base_path('webpack.mix.js'),
            __DIR__.'/../../stubs/resources/views/layouts' => resource_path('views/layouts'),
            __DIR__.'/../../stubs/resources/css' => resource_path('css'),
            __DIR__.'/../../stubs/resources/js' => resource_path('js'),
            __DIR__.'/../../resources/views/errors' => resource_path('views/errors'),
            __DIR__.'/../../resources/views/vendor' => resource_path('views/vendor'),
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
                __DIR__.'/../../resources/views/'.$module => resource_path('views/vendor/atom/'.$module),
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