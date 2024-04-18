<?php

namespace Jiannius\Atom\Providers;

use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\ServiceProvider;

class AtomServiceProvider extends ServiceProvider
{
    // register
    public function register() : void
    {        
        $this->mergeConfigFrom(__DIR__.'/../../stubs/config/atom.php', 'atom');
    }

    // boot
    public function boot() : void
    {
        // helpers
        require_once __DIR__.'/../Helpers/Core.php';
        require_once __DIR__.'/../Helpers/Atom.php';
        require_once __DIR__.'/../Helpers/Component.php';
        require_once __DIR__.'/../Helpers/Database.php';
        require_once __DIR__.'/../Helpers/Route.php';

        $this->loadServiceContainers();
        $this->loadViewsFrom(__DIR__.'/../../resources/views', 'atom');
        $this->loadTranslationsFrom(__DIR__.'/../../lang', 'atom');
        
        // custom polymorphic types
        if ($morphMap = config('atom.morph_map')) {
            Relation::enforceMorphMap($morphMap);
        }

        if ($this->app->runningInConsole()) {
            // publishing
            $this->registerStaticPublishing();
            $this->registerBasePublishing();
        }
    }

    // load service containers
    public function loadServiceContainers() : void
    {
        $this->app->singleton('breadcrumbs', fn() => new \Jiannius\Atom\Services\Breadcrumbs);
        if (($breadcrumbs = base_path('routes/breadcrumbs.php')) && file_exists($breadcrumbs)) {
            $this->loadRoutesFrom($breadcrumbs);
        }

        $this->app->bind('route', fn() => new \Jiannius\Atom\Services\Route);
        $this->app->bind('ipay', fn() => new \Jiannius\Atom\Services\Ipay);
        $this->app->bind('stripe', fn() => new \Jiannius\Atom\Services\Stripe);
        $this->app->bind('revenue_monster', fn() => new \Jiannius\Atom\Services\RevenueMonster);
    }

    // register publishing for static site
    public function registerStaticPublishing() : void
    {
        $this->publishes([
            __DIR__.'/../../stubs-static/config' => base_path('config'),
            __DIR__.'/../../stubs-static/resources' => base_path('resources'),
            __DIR__.'/../../stubs-static/tailwind.config.js' => base_path('tailwind.config.js'),
            __DIR__.'/../../stubs-static/postcss.config.js' => base_path('postcss.config.js'),
            __DIR__.'/../../stubs-static/vite.config.js' => base_path('vite.config.js'),
        ], 'atom-install-static');
    }

    // register base publishing
    public function registerBasePublishing() : void
    {
        $this->publishes([
            __DIR__.'/../../stubs/app' => base_path('app'),
            __DIR__.'/../../stubs/config' => base_path('config'),
            __DIR__.'/../../stubs/resources' => base_path('resources'),
            __DIR__.'/../../stubs/routes' => base_path('routes'),
            __DIR__.'/../../stubs/tailwind.config.js' => base_path('tailwind.config.js'),
            __DIR__.'/../../stubs/postcss.config.js' => base_path('postcss.config.js'),
            __DIR__.'/../../stubs/vite.config.js' => base_path('vite.config.js'),
        ], 'atom-base');
    }
}