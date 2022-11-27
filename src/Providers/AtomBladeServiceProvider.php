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
}