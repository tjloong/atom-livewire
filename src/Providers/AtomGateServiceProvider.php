<?php

namespace Jiannius\Atom\Providers;

use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AtomGateServiceProvider extends ServiceProvider
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
        if (config('atom.static_site')) return;
        
        Gate::before(function ($user, $permission) {
            if (!enabled_module('permissions')) return true;

            $permission = str($permission)->replace('-', '_')->toString();
            $splits = explode('.', $permission);
            $module = $splits[0];
            $action = $splits[1] ?? null;
            $actions = config('atom.app.permissions.'.$module, []);
            $isActionDefined = in_array($action, $actions);

            if (!$isActionDefined) return true;
            if ($user->is_root) return true;

            if (enabled_module('roles')) {
                return $user->permissions()->granted($permission)->count() > 0 || (
                    !$user->permissions()->forbidden($permission)->count()
                    && $user->role
                    && $user->role->can($permission)
                );
            }
            else {
                return $user->permissions()->granted($permission)->count() > 0;
            }
        });
    }
}