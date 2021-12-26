<?php

namespace Jiannius\Atom;

use Livewire\Livewire;
use App\Models\Ability;
use Illuminate\Routing\Router;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use Jiannius\Atom\Console\InstallCommand;
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
        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');

        require_once __DIR__.'/Helpers.php';

        $this->registerBlade();
        $this->registerComponents();
        $this->registerMiddlewares();

        if (!config('atom.static_site')) {
            $this->registerRoutes();
            $this->registerGates();
        }

        $this->registerPublishing();
        $this->registerCommands();
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
        Blade::component('stat-box', 'Jiannius\\Atom\\Components\\StatBox');
        Blade::component('dropdown', 'Jiannius\\Atom\\Components\\Dropdown');
        Blade::component('checkbox', 'Jiannius\\Atom\\Components\\Checkbox');
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
        Blade::component('input.picker', 'Jiannius\\Atom\\Components\\Input\\Picker');
        Blade::component('input.search', 'Jiannius\\Atom\\Components\\Input\\Search');
        Blade::component('input.select', 'Jiannius\\Atom\\Components\\Input\\Select');
        Blade::component('input.currency', 'Jiannius\\Atom\\Components\\Input\\Currency');
        Blade::component('input.password', 'Jiannius\\Atom\\Components\\Input\\Password');
        Blade::component('input.checkbox', 'Jiannius\\Atom\\Components\\Input\\Checkbox');
        Blade::component('input.textarea', 'Jiannius\\Atom\\Components\\Input\\Textarea');
        Blade::component('input.richtext', 'Jiannius\\Atom\\Components\\Input\\Richtext');
        Blade::component('input.sortable', 'Jiannius\\Atom\\Components\\Input\\Sortable');
        Livewire::component('input.file', 'Jiannius\\Atom\\Components\\Input\\File');
        
        Blade::component('tabs', 'Jiannius\\Atom\\Components\\Tabs\\Index');
        Blade::component('tabs.tab', 'Jiannius\\Atom\\Components\\Tabs\\Tab');

        Blade::component('sidenav', 'Jiannius\\Atom\\Components\\Sidenav\\Index');
        Blade::component('sidenav.item', 'Jiannius\\Atom\\Components\\Sidenav\\Item');
        
        Blade::component('notify.alert', 'Jiannius\\Atom\\Components\\Notify\\Alert');
        Blade::component('notify.toast', 'Jiannius\\Atom\\Components\\Notify\\Toast');
        Blade::component('notify.confirm', 'Jiannius\\Atom\\Components\\Notify\\Confirm');

        Blade::component('builder.faq', 'Jiannius\\Atom\\Components\\Builder\\Faq');
        Blade::component('builder.hero', 'Jiannius\\Atom\\Components\\Builder\\Hero');
        Blade::component('builder.footer', 'Jiannius\\Atom\\Components\\Builder\\Footer');
        Blade::component('builder.slider', 'Jiannius\\Atom\\Components\\Builder\\Slider');
        Blade::component('builder.navbar', 'Jiannius\\Atom\\Components\\Builder\\Navbar');
        Blade::component('builder.breadcrumb', 'Jiannius\\Atom\\Components\\Builder\\Breadcrumb');
        Blade::component('builder.testimonial', 'Jiannius\\Atom\\Components\\Builder\\Testimonial');
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
     * Register publishing
     * 
     * @return void
     */
    public function registerPublishing()
    {
        if (!$this->app->runningInConsole()) return;

        $this->publishes([
            __DIR__.'/../stubs/app' => base_path('app'),
            __DIR__.'/../stubs/config' => base_path('config'),
            __DIR__.'/../stubs/routes' => base_path('routes'),
            __DIR__.'/../stubs/storage/app' => base_path('storage/app'),
            __DIR__.'/../stubs/resources' => base_path('resources'),
            __DIR__.'/../stubs/.env.prod' => base_path('.env.prod'),
            __DIR__.'/../stubs/.env.staging' => base_path('.env.staging'),
            __DIR__.'/../stubs/jsconfig.json' => base_path('jsconfig.json'),
            __DIR__.'/../stubs/tailwind.config.js' => base_path('tailwind.config.js'),
            __DIR__.'/../stubs/webpack.config.js' => base_path('webpack.config.js'),
            __DIR__.'/../stubs/webpack.mix.js' => base_path('webpack.mix.js'),
        ], 'atom');

        // static publishing
        $this->publishes([
            __DIR__.'/../stubs-static/app' => base_path('app'),
            __DIR__.'/../stubs-static/config' => base_path('config'),
            __DIR__.'/../stubs-static/routes' => base_path('routes'),
            __DIR__.'/../stubs-static/storage/app' => base_path('storage/app'),
            __DIR__.'/../stubs-static/resources' => base_path('resources'),
            __DIR__.'/../stubs-static/.env.prod' => base_path('.env.prod'),
            __DIR__.'/../stubs-static/.env.staging' => base_path('.env.staging'),
            __DIR__.'/../stubs-static/jsconfig.json' => base_path('jsconfig.json'),
            __DIR__.'/../stubs-static/tailwind.config.js' => base_path('tailwind.config.js'),
            __DIR__.'/../stubs-static/webpack.config.js' => base_path('webpack.config.js'),
            __DIR__.'/../stubs-static/webpack.mix.js' => base_path('webpack.mix.js'),
        ], 'atom-static');
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
        ]);
    }
}