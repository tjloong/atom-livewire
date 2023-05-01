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
        
        Gate::before(function ($user, $action) {
            if ($user->isTier('root')) return true;

            if (str($action)->is('role:*')) return $this->checkRoles($user, $action);   // eg. @can('role:admin|sales')
            else if (str($action)->is('tier:*')) return $this->checkTiers($user, $action);  // eg. @can('tier:root')
            else if ($action === 'plan' || str($action)->is('plan:*')) return $this->checkPlans($user, $action); // eg. @can('plan:starter')
            else return $this->checkPermissions($user, $action); // eg. @can('contact.view')
        });
    }

    /**
     * Check roles
     */
    public function checkRoles($user, $action): bool
    {
        if (!enabled_module('roles')) return true;

        $roles = $this->actionToParams(str($action)->replaceFirst('role:', ''));

        return $user->isRole($roles);
    }

    /**
     * Check tiers
     */
    public function checkTiers($user, $action): bool
    {
        $tiers = $this->actionToParams(str($action)->replaceFirst('tier:', ''));

        return $user->isTier($tiers);
    }

    /**
     * Check plans
     */
    public function checkPlans($user, $action): bool
    {
        if (!enabled_module('plans')) return true;

        $isSessionUser = $user->id === user('id');
        $subscribedPlans = $isSessionUser ? session('can.plans') : [];

        if (!$subscribedPlans) {
            $subscribedPlans = collect();
            $subscriptions = $user->subscriptions()->with('price')->status('active')->get();

            foreach ($subscriptions as $subscription) {
                $subscribedPlans->push($subscription->price->code);
                $subscribedPlans->push($subscription->price->plan->code);
            }

            $subscribedPlans = $subscribedPlans->unique()->values()->all();

            if ($isSessionUser) session(['can.plans' => $subscribedPlans]);
        }

        $plans = $this->actionToParams(str($action)->replaceFirst('plan:', ''));

        if ($plans === 'plan') return count($subscribedPlans) > 0;  // @can('plan') will check user subscribed to any plan
        else {
            return collect($plans)
                ->filter(function($plan) use ($subscribedPlans) {
                    if (str($plan)->endsWith('*') || str($plan)->startsWith('*')) {
                        return !empty(collect($subscribedPlans)->first(fn($val) => str($val)->is($plan)));
                    }
                    else return in_array($plan, $subscribedPlans);
                })
                ->count() > 0;
        }
    }

    /**
     * Check permissions
     */
    public function checkPermissions($user, $action): bool
    {
        if (!enabled_module('permissions')) return true;

        $isSessionUser = $user->id === user('id');
        $action = str($action)->replace('-', '_')->toString();
        $permitted = $isSessionUser ? session('can.permissions') : [];

        if ($isSessionUser && tenant() && $user->isTenantOwner()) return true;

        if (!$permitted) {
            $permissions = collect(model('permission')->getPermissionList())
                ->map(fn($actions, $module) => collect($actions)->map(fn($val) => $module.'.'.$val)->toArray())
                ->collapse();

            foreach ($permissions as $permission) {
                $query = model('permission')
                    ->where('permission', $permission)
                    ->where('user_id', $user->id)
                    ->when(tenant() && $isSessionUser, fn($q) => $q->where('tenant_id', tenant('id')));

                $isForbidden = (clone $query)->where('is_granted', false)->count() > 0;
                $isGranted = (clone $query)->where('is_granted', true)->count() > 0;

                $permitted[$permission] = $isForbidden ? false : $isGranted;
            }

            if ($isSessionUser) session(['can.permissions' => $permitted]);
        }

        $action = collect($action)->filter(fn($val) => isset($permitted[$val]));

        // action is not defined, so return true
        if (!$action->count()) return true;

        return collect($action)
            ->filter(fn($val) => $permitted[$val] === true)
            ->count() > 0;
    }

    /**
     * Action to params
     */
    public function actionToParams($action): mixed
    {
        if ($action->is('*|*')) return explode('|', $action->toString());
        elseif ($action->is('*,*')) return explode(',', $action->toString());
        elseif ($action->is('*/*')) return explode('/', $action->toString());
        else return $action->toString();
    }
}