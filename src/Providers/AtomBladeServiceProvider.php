<?php

namespace Jiannius\Atom\Providers;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;

class AtomBladeServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }
    
    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Blade::directive('cdn', function($expression) {
            return "<?php foreach (app('cdn')->get({$expression}) as \$cdndata) { 
                Basset::basset(get(\$cdndata, 'url'), true, get(\$cdndata, 'attr', []));
            } ?>";
        });

        Blade::if('route', function() {
            return current_route(func_get_args());
        });

        Blade::if('notroute', function() {
            return !current_route(func_get_args());
        });

        Blade::if('tier', function($value) {
            return tier($value);
        });

        Blade::if('nottier', function($value) {
            return !tier($value);
        });

        Blade::if('role', function($value) {
            return user()->can('role', $value);
        });

        Blade::if('notrole', function($value) {
            return !user()->can('role', $value);
        });
    }
}