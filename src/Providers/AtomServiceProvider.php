<?php

namespace Jiannius\Atom\Providers;

use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\ServiceProvider;
use Illuminate\View\ComponentAttributeBag;

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
        $this->app->bind('cdn', fn() => new \Jiannius\Atom\Services\CDN);

        // macros
        $this->macros();

        // custom polymorphic types
        if ($morphMap = config('atom.morph_map')) {
            Relation::enforceMorphMap($morphMap);
        }

        if ($this->app->runningInConsole()) {
            // basset cache paths
            config([
                'backpack.basset.view_paths' => [
                    resource_path('views'),
                    atom_path('resources/views'),
                ],
            ]);

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

    // macros
    public function macros() : void
    {
        if (!Request::hasMacro('portal')) {
            Request::macro('portal', function() {
                $route = $this->route()?->getName();

                if (in_array($route, ['login', 'logout', 'register', 'password.forgot', 'password.reset'])) {
                    return 'auth';
                }
                else if ($route) {
                    $portal = collect(explode('.', $route))->first();

                    if (str($portal)->startsWith('__') || in_array($portal, ['socialite'])) return null;
                    else return $portal;
                }

                return null;
            });
        }

        if (!ComponentAttributeBag::hasMacro('hasLike')) {
            ComponentAttributeBag::macro('hasLike', function() {
                $value = func_get_args();
                $keys = collect($this->getAttributes())->keys();

                return !empty(
                    $keys->first(fn($key) => str($key)->is($value))
                );
            });
        }

        if (!Carbon::hasMacro('local')) {
            Carbon::macro('local', function() {
                $tz = optional(user())->settings('timezone') ?? config('atom.timezone');
                return $tz ? $this->timezone($tz) : $this;
            });
        }

        if (!Carbon::hasMacro('pretty')) {
            Carbon::macro('pretty', function($option = 'date') {
                if ($option === 'date') $format = 'd M Y';
                if ($option === 'datetime') $format = 'd M Y g:iA';
                if ($option === 'datetime-24') $format = 'd M Y H:i:s';
                if ($option === 'time') $format = 'g:i A';
                if ($option === 'time-24') $format = 'H:i:s';

                return $this->local()->format($format);
            });
        }
    }
}