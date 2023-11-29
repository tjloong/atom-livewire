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
            __DIR__.'/../../stubs-static/tailwind.config.js' => base_path('tailwind.config.js'),
            __DIR__.'/../../stubs-static/postcss.config.js' => base_path('postcss.config.js'),
            __DIR__.'/../../stubs-static/vite.config.js' => base_path('vite.config.js'),
            __DIR__.'/../../stubs-static/resources/views/layouts' => resource_path('views/layouts'),
            __DIR__.'/../../stubs-static/resources/css' => resource_path('css'),
            __DIR__.'/../../stubs-static/resources/js' => resource_path('js'),
            __DIR__.'/../../resources/views/errors' => resource_path('views/errors'),
            __DIR__.'/../../resources/views/vendor' => resource_path('views/vendor'),
        ], 'atom-install-static');
    }

    // register base publishing
    public function registerBasePublishing() : void
    {
        $this->publishes([
            __DIR__.'/../../stubs/config' => base_path('config'),
            __DIR__.'/../../stubs/tailwind.config.js' => base_path('tailwind.config.js'),
            __DIR__.'/../../stubs/postcss.config.js' => base_path('postcss.config.js'),
            __DIR__.'/../../stubs/vite.config.js' => base_path('vite.config.js'),
            __DIR__.'/../../stubs/resources/views/layouts' => base_path('resources/views/layouts'),
            __DIR__.'/../../stubs/resources/css' => base_path('resources/css'),
            __DIR__.'/../../stubs/resources/js' => base_path('resources/js'),
            __DIR__.'/../../resources/views/errors' => base_path('resources/views/errors'),
            __DIR__.'/../../resources/views/vendor' => base_path('resources/views/vendor'),
        ], 'atom-base');
    }
}