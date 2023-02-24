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
        
        Gate::before(function ($user, $name) {
            if (!enabled_module('permissions')) return true;

            $name = str($name)->replace('-', '_')->toString();
            $splits = explode('.', $name);
            $module = $splits[0];
            $action = $splits[1] ?? null;
            $actions = data_get(model('permission')->getActions(), $module, []);
            $isActionDefined = in_array($action, $actions);

            if (!$isActionDefined) return true;
            if ($user->isTier('root')) return true;

            if (enabled_module('roles')) {
                return $user->permissions()->granted($name)->count() > 0 || (
                    !$user->permissions()->forbidden($name)->count()
                    && $user->role
                    && $user->role->can($name)
                );
            }
            else {
                return $user->permissions()->granted($name)->count() > 0;
            }
        });
    }
}