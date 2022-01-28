<?php

namespace Jiannius\Atom;

use Livewire\Livewire;
use Illuminate\Routing\Router;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Route;
use Jiannius\Atom\Models\Ability;
use Jiannius\Atom\Models\SiteSetting;
use Jiannius\Atom\Console\ViewsCommand;
use Jiannius\Atom\Console\AssetsCommand;
use Jiannius\Atom\Console\LayoutsCommand;
use Jiannius\Atom\Console\InstallCommand;
use Jiannius\Atom\Console\FeaturesCommand;
use Jiannius\Atom\Middleware\IsRole;
use Jiannius\Atom\Middleware\TrackReferer;

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

        SiteSetting::configureSMTP();
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

        Blade::if('feature', function($value) {
            return enabled_feature($value);
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
        Blade::component('alert', 'Jiannius\\Atom\\Components\\Alert');
        Blade::component('badge', 'Jiannius\\Atom\\Components\\Badge');
        Blade::component('image', 'Jiannius\\Atom\\Components\\Image');
        Blade::component('modal', 'Jiannius\\Atom\\Components\\Modal');
        Blade::component('loader', 'Jiannius\\Atom\\Components\\Loader');
        Blade::component('button', 'Jiannius\\Atom\\Components\\Button');
        Blade::component('drawer', 'Jiannius\\Atom\\Components\\Drawer');
        Blade::component('fbpixel', 'Jiannius\\Atom\\Components\\FbPixel');
        Blade::component('sidenav', 'Jiannius\\Atom\\Components\\Sidenav');
        Blade::component('stat-box', 'Jiannius\\Atom\\Components\\StatBox');
        Blade::component('dropdown', 'Jiannius\\Atom\\Components\\Dropdown');
        Blade::component('checkbox', 'Jiannius\\Atom\\Components\\Checkbox');
        Blade::component('atom-logo', 'Jiannius\\Atom\\Components\\AtomLogo');
        Blade::component('file-card', 'Jiannius\\Atom\\Components\\FileCard');
        Blade::component('admin-panel', 'Jiannius\\Atom\\Components\\AdminPanel');
        Blade::component('empty-state', 'Jiannius\\Atom\\Components\\EmptyState');
        Blade::component('page-header', 'Jiannius\\Atom\\Components\\PageHeader');
        Blade::component('fullscreen-loader', 'Jiannius\\Atom\\Components\\FullscreenLoader');

        Blade::component('table', 'Jiannius\\Atom\\Components\\Table\\Index');
        Blade::component('table.head', 'Jiannius\\Atom\\Components\\Table\\Head');
        Blade::component('table.row', 'Jiannius\\Atom\\Components\\Table\\Row');
        Blade::component('table.cell', 'Jiannius\\Atom\\Components\\Table\\Cell');
        Blade::component('table.button', 'Jiannius\\Atom\\Components\\Table\\Button');
        
        Blade::component('input.seo', 'Jiannius\\Atom\\Components\\Input\\Seo');
        Blade::component('input.text', 'Jiannius\\Atom\\Components\\Input\\Text');
        Blade::component('input.tags', 'Jiannius\\Atom\\Components\\Input\\Tags');
        Blade::component('input.date', 'Jiannius\\Atom\\Components\\Input\\Date');
        Blade::component('input.slug', 'Jiannius\\Atom\\Components\\Input\\Slug');
        Blade::component('input.email', 'Jiannius\\Atom\\Components\\Input\\Email');
        Blade::component('input.field', 'Jiannius\\Atom\\Components\\Input\\Field');
        Blade::component('input.image', 'Jiannius\\Atom\\Components\\Input\\Image');
        Blade::component('input.radio', 'Jiannius\\Atom\\Components\\Input\\Radio');
        Blade::component('input.title', 'Jiannius\\Atom\\Components\\Input\\Title');
        Blade::component('input.phone', 'Jiannius\\Atom\\Components\\Input\\Phone');
        Blade::component('input.state', 'Jiannius\\Atom\\Components\\Input\\State');
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
        
        Blade::component('notify.alert', 'Jiannius\\Atom\\Components\\Notify\\Alert');
        Blade::component('notify.toast', 'Jiannius\\Atom\\Components\\Notify\\Toast');
        Blade::component('notify.confirm', 'Jiannius\\Atom\\Components\\Notify\\Confirm');

        Blade::component('builder.faq', 'Jiannius\\Atom\\Components\\Builder\\Faq');
        Blade::component('builder.hero', 'Jiannius\\Atom\\Components\\Builder\\Hero');
        Blade::component('builder.share', 'Jiannius\\Atom\\Components\\Builder\\Share');
        Blade::component('builder.footer', 'Jiannius\\Atom\\Components\\Builder\\Footer');
        Blade::component('builder.slider', 'Jiannius\\Atom\\Components\\Builder\\Slider');
        Blade::component('builder.navbar', 'Jiannius\\Atom\\Components\\Builder\\Navbar');
        Blade::component('builder.breadcrumb', 'Jiannius\\Atom\\Components\\Builder\\Breadcrumb');
        Blade::component('builder.testimonial', 'Jiannius\\Atom\\Components\\Builder\\Testimonial');
    }

    /**
     * Register livewires
     * 
     * @return void
     */
    public function registerLivewires()
    {
        Livewire::component('atom.home', 'Jiannius\\Atom\\Http\\Livewire\\Web\\Home');
        Livewire::component('atom.dashboard', 'Jiannius\\Atom\\Http\\Livewire\\App\\Dashboard');

        // auth
        Livewire::component('atom.auth.login', 'Jiannius\\Atom\\Http\\Livewire\\Auth\\Login');
        Livewire::component('atom.auth.register', 'Jiannius\\Atom\\Http\\Livewire\\Auth\\Register');
        Livewire::component('atom.auth.register-form', 'Jiannius\\Atom\\Http\\Livewire\\Auth\\RegisterForm');
        Livewire::component('atom.auth.reset-password', 'Jiannius\\Atom\\Http\\Livewire\\Auth\\ResetPassword');
        Livewire::component('atom.auth.forgot-password', 'Jiannius\\Atom\\Http\\Livewire\\Auth\\ForgotPassword');
        
        // blog
        Livewire::component('atom.blog.create', 'Jiannius\\Atom\\Http\\Livewire\\App\\Blog\\Create');
        Livewire::component('atom.blog.listing', 'Jiannius\\Atom\\Http\\Livewire\\App\\Blog\\Listing');
        Livewire::component('atom.blog.update', 'Jiannius\\Atom\\Http\\Livewire\\App\\Blog\\Update');
        Livewire::component('atom.blog.form.content', 'Jiannius\\Atom\\Http\\Livewire\\App\\Blog\\Form\\Content');
        Livewire::component('atom.blog.form.seo', 'Jiannius\\Atom\\Http\\Livewire\\App\\Blog\\Form\\Seo');
        Livewire::component('atom.blog.form.settings', 'Jiannius\\Atom\\Http\\Livewire\\App\\Blog\\Form\\Settings');

        // enquiry
        Livewire::component('atom.enquiry.listing', 'Jiannius\\Atom\\Http\\Livewire\\App\\Enquiry\\Listing');
        Livewire::component('atom.enquiry.update', 'Jiannius\\Atom\\Http\\Livewire\\App\\Enquiry\\Update');

        // page
        Livewire::component('atom.page.listing', 'Jiannius\\Atom\\Http\\Livewire\\App\\Page\\Listing');
        Livewire::component('atom.page.update', 'Jiannius\\Atom\\Http\\Livewire\\App\\Page\\Update');
        Livewire::component('atom.page.form', 'Jiannius\\Atom\\Http\\Livewire\\App\\Page\\Form');

        // user
        Livewire::component('atom.user.account', 'Jiannius\\Atom\\Http\\Livewire\\App\\User\\Account');
        Livewire::component('atom.user.listing', 'Jiannius\\Atom\\Http\\Livewire\\App\\User\\Listing');
        Livewire::component('atom.user.create', 'Jiannius\\Atom\\Http\\Livewire\\App\\User\\Create');
        Livewire::component('atom.user.update', 'Jiannius\\Atom\\Http\\Livewire\\App\\User\\Update');
        Livewire::component('atom.user.form', 'Jiannius\\Atom\\Http\\Livewire\\App\\User\\Form');

        // role
        Livewire::component('atom.role.listing', 'Jiannius\\Atom\\Http\\Livewire\\App\\Role\\Listing');
        Livewire::component('atom.role.create', 'Jiannius\\Atom\\Http\\Livewire\\App\\Role\\Create');
        Livewire::component('atom.role.update', 'Jiannius\\Atom\\Http\\Livewire\\App\\Role\\Update');
        Livewire::component('atom.role.form', 'Jiannius\\Atom\\Http\\Livewire\\App\\Role\\Form');

        // ability
        Livewire::component('atom.ability.listing', 'Jiannius\\Atom\\Http\\Livewire\\App\\Ability\\Listing');

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
        Livewire::component('atom.file.form', 'Jiannius\\Atom\\Http\\Livewire\\App\\File\\Form');
        Livewire::component('atom.file.listing', 'Jiannius\\Atom\\Http\\Livewire\\App\\File\\Listing');
        Livewire::component('atom.file.uploader', 'Jiannius\\Atom\\Http\\Livewire\\App\\File\\Uploader');

        // site settings
        Livewire::component('atom.site-settings.update', 'Jiannius\\Atom\\Http\\Livewire\\App\\SiteSettings\\Update');
        Livewire::component('atom.site-settings.form.contact', 'Jiannius\\Atom\\Http\\Livewire\\App\\SiteSettings\\Form\\Contact');
        Livewire::component('atom.site-settings.form.email', 'Jiannius\\Atom\\Http\\Livewire\\App\\SiteSettings\\Form\\Email');
        Livewire::component('atom.site-settings.form.seo', 'Jiannius\\Atom\\Http\\Livewire\\App\\SiteSettings\\Form\\Seo');
        Livewire::component('atom.site-settings.form.social-media', 'Jiannius\\Atom\\Http\\Livewire\\App\\SiteSettings\\Form\\SocialMedia');
        Livewire::component('atom.site-settings.form.storage', 'Jiannius\\Atom\\Http\\Livewire\\App\\SiteSettings\\Form\\Storage');
        Livewire::component('atom.site-settings.form.tracking', 'Jiannius\\Atom\\Http\\Livewire\\App\\SiteSettings\\Form\\Tracking');
    }

    /**
     * Register middlewares
     * 
     * @return void
     */
    public function registerMiddlewares()
    {
        $router = $this->app->make(Router::class);
        $router->aliasMiddleware('role', IsRole::class);
        $router->aliasMiddleware('referer', TrackReferer::class);
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
        if (!enabled_feature('abilities')) return;

        Gate::before(function ($user, $action) {
            if ($user->isRole('root')) return true;
            if ($user->isRole('admin')) return true;

            $split = explode('.', $action);
            $module = head($split);
            $name = last($split);
            $ability = Ability::where('module', $module)->where('name', $name)->first();

            if (!$ability) return false;

            $isForbidden = $user->abilities()->where('abilities.id', $ability->id)->wherePivot('access', 'forbid')->count() > 0;
            $isGranted = $user->abilities()->where('abilities.id', $ability->id)->wherePivot('access', 'grant')->count() > 0;
            $isInRole = $user->role->abilities()->where('abilities.id', $ability->id)->count() > 0;
    
            return !$isForbidden && ($isGranted || $isInRole);
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
            __DIR__.'/../resources/views/errors' => resource_path('views/errors'),
            __DIR__.'/../resources/views/vendor' => resource_path('views/vendor'),
        ], 'atom-installation-static');

        $this->publishes([
            __DIR__.'/../stubs-static/resources/views/layouts' => resource_path('views/layouts'),
        ], 'atom-layouts-static');

        $this->publishes([
            __DIR__.'/../stubs-static/resources/css' => resource_path('css'),
            __DIR__.'/../stubs-static/resources/js' => resource_path('js'),
        ], 'atom-assets-static');
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
            __DIR__.'/../resources/views/errors' => resource_path('views/errors'),
            __DIR__.'/../resources/views/vendor' => resource_path('views/vendor'),
        ], 'atom-installation');

        $this->publishes([
            __DIR__.'/../stubs/resources/views/layouts' => resource_path('views/layouts'),
        ], 'atom-layouts');

        $this->publishes([
            __DIR__.'/../stubs/resources/css' => resource_path('css'),
            __DIR__.'/../stubs/resources/js' => resource_path('js'),
        ], 'atom-assets');

        $features = [
            'user', 'role', 'file', 'site-settings', 
            'label', 'page', 'ability', 'team', 'blog', 'enquiry'
        ];

        foreach ($features as $feature) {
            $this->publishes([
                __DIR__.'/../resources/views/app/' . $feature => resource_path('views/vendor/atom/app/' . $feature),
            ], 'atom-views-' . $feature);
        }

        $this->publishes([
            __DIR__.'/../resources/views/auth' => resource_path('views/vendor/atom/auth'),
        ], 'atom-views-auth');

        $this->publishes([
            __DIR__.'/../resources/views/web' => resource_path('views/vendor/atom/web'),
        ], 'atom-views-web');
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
            ViewsCommand::class,
            AssetsCommand::class,
            InstallCommand::class,
            LayoutsCommand::class,
            FeaturesCommand::class,
        ]);
    }
}