<?php

namespace Jiannius\Atom\Providers;

use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\ServiceProvider;

class AtomServiceProvider extends ServiceProvider
{
    // register
    public function register() : void
    {        
        //
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

        $this->loadViewsFrom(__DIR__.'/../../resources/views', 'atom');
        $this->loadTranslationsFrom(__DIR__.'/../../lang', 'atom');

        $this->app->bind('route', fn() => new \Jiannius\Atom\Services\Route);

        // custom polymorphic types
        if ($morphMap = config('atom.morph_map')) {
            Relation::enforceMorphMap($morphMap);
        }

        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/../../publishes/app' => base_path('app'),
                __DIR__.'/../../publishes/config' => base_path('config'),
                __DIR__.'/../../publishes/resources' => base_path('resources'),
                __DIR__.'/../../publishes/routes' => base_path('routes'),
                __DIR__.'/../../publishes/tailwind.config.js' => base_path('tailwind.config.js'),
                __DIR__.'/../../publishes/postcss.config.js' => base_path('postcss.config.js'),
                __DIR__.'/../../publishes/vite.config.js' => base_path('vite.config.js'),
            ], 'atom');
        }
    }
}