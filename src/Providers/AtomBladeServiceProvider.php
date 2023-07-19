<?php

namespace Jiannius\Atom\Providers;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;

class AtomBladeServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Blade::if('route', function($value) {
            return collect((array)$value)->contains(fn($name) => current_route($name));
        });

        Blade::if('notroute', function($value) {
            return !collect((array)$value)->contains(fn($name) => current_route($name));
        });

        Blade::if('tier', function($value) {
            return tier($value);
        });

        Blade::if('nottier', function($value) {
            return !tier($value);
        });

        Blade::if('role', function($value) {
            return tier('root') || user()->isRole($value);
        });

        Blade::if('notrole', function($value) {
            return !user()->isRole($value);
        });
    }
}