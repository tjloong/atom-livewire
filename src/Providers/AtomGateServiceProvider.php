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
            if ($user->isTier('root')) return true;

            // role checking, eg: @can('role:admin,sales')
            if (str($name)->is('role:*')) {
                if (!enabled_module('roles')) return true;

                $name = str($name)->replaceFirst('role:', '');

                if ($name->is('*|*')) $roles = explode('|', $name->toString());
                elseif ($name->is('*,*')) $roles = explode(',', $name->toString());
                elseif ($name->is('*/*')) $roles = explode('/', $name->toString());
                else $roles = $name->toString();

                return $user->isRole($roles);
            }
            // tier checking, eg: @can('tier:root')
            else if (str($name)->is('tier:*')) {
                $name = str($name)->replaceFirst('tier:', '');

                if ($name->is('*|*')) $tiers = explode('|', $name->toString());
                elseif ($name->is('*,*')) $tiers = explode(',', $name->toString());
                elseif ($name->is('*/*')) $tiers = explode('/', $name->toString());
                else $tiers = $name->toString();

                return $user->isTier($tiers);
            }
            // permission checking
            else {
                if (!enabled_module('permissions')) return true;

                $name = str($name)->replace('-', '_')->toString();
                $splits = explode('.', $name);
                $module = $splits[0];
                $action = $splits[1] ?? null;
                $actions = data_get(model('permission')->getActions(), $module, []);
                $isActionDefined = in_array($action, $actions);
    
                if (!$isActionDefined) return true;
    
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
            }
        });
    }
}