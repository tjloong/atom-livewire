<?php

namespace Jiannius\Atom\Providers;

use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AtomGateServiceProvider extends ServiceProvider
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
        $policy = find_class('policy');

        Gate::define('tier', [$policy, 'tier']);
        Gate::define('role', [$policy, 'role']);
        Gate::define('permission', [$policy, 'permission']);
        Gate::define('perm', [$policy, 'permission']);
    }
}