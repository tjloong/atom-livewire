<?php

namespace Jiannius\Atom\Providers;

use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\ServiceProvider;

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

        // helpers
        require_once __DIR__.'/../Helpers/Core.php';
        require_once __DIR__.'/../Helpers/Atom.php';
        require_once __DIR__.'/../Helpers/Component.php';
        require_once __DIR__.'/../Helpers/Database.php';
        require_once __DIR__.'/../Helpers/Route.php';
        require_once __DIR__.'/../Helpers/PaymentGateway.php';
        
        // middleware
        $router = app('router');
        $router->aliasMiddleware('track-ref', \Jiannius\Atom\Http\Middleware\TrackReferer::class);

        // custom polymorphic types
        if ($morphMap = config('atom.morph_map')) {
            Relation::enforceMorphMap($morphMap);
        }

        if ($this->app->runningInConsole()) {
            // commands
            $this->commands([
                \Jiannius\Atom\Console\ViewCommand::class,
                \Jiannius\Atom\Console\InstallCommand::class,
                \Jiannius\Atom\Console\PublishCommand::class,
                \Jiannius\Atom\Console\RefreshCommand::class,
                \Jiannius\Atom\Console\RepairCommand::class,
                \Jiannius\Atom\Console\CkeditorCommand::class,
                \Jiannius\Atom\Console\FontawesomeCommand::class,
            ]);

            // publishing
            $this->registerStaticPublishing();
            $this->registerBasePublishing();
        }
    }

    /**
     * Register publishing for static site
     * 
     * @return void
     */
    public function registerStaticPublishing()
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

    /**
     * Register base publishing
     * 
     * @return void
     */
    public function registerBasePublishing()
    {
        $this->publishes([
            __DIR__.'/../../stubs/config' => base_path('config'),
            __DIR__.'/../../stubs/app/Models' => app_path('Models'),
            __DIR__.'/../../stubs/tailwind.config.js' => base_path('tailwind.config.js'),
            __DIR__.'/../../stubs/postcss.config.js' => base_path('postcss.config.js'),
            __DIR__.'/../../stubs/vite.config.js' => base_path('vite.config.js'),
            __DIR__.'/../../stubs/resources/views/layouts' => base_path('resources/views/layouts'),
            __DIR__.'/../../stubs/resources/css' => base_path('resources/css'),
            __DIR__.'/../../stubs/resources/js' => base_path('resources/js'),
            __DIR__.'/../../stubs/routes' => base_path('routes'),
            __DIR__.'/../../resources/views/errors' => base_path('resources/views/errors'),
            __DIR__.'/../../resources/views/vendor' => base_path('resources/views/vendor'),
        ], 'atom-base');
    }
}