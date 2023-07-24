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
        if (config('atom.static_site')) return;

        Gate::define('tier', fn($user, $tiers) => $user->isTier(explode_if(['|', ',', '/'], $tiers)));

        if (has_table('roles')) {
            Gate::define('role', fn($user, $roles) => $user->isRole(explode_if(['|', ',', '/'], $roles)));
        }

        if (has_table('permissions')) {
            Gate::define('perm', fn($user, $permission) => $user->isPermitted(explode_if(['|', ',', '/'], $permission)));
        }
    }
}