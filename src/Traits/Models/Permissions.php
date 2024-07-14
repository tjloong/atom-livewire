<?php

namespace Jiannius\Atom\Traits\Models;

use Illuminate\Database\Eloquent\Relations\HasMany;

// this trait should be consumed by user model
trait Permissions
{
    // boot
    protected static function bootPermissions() : void
    {
        static::saved(function($user) {
            $user->clearPermissionsCache();
        });
        
        static::deleted(function($user) {
            $user->clearPermissionsCache();
        });
    }

    // get permissions for model
    public function permissions() : HasMany
    {
        return $this->hasMany(model('permission'));
    }

    // check model is permitted
    public function isPermitted(...$actions) : bool
    {
        $permittedActions = $this->getPermittedActions();

        return collect($actions)
            ->filter(fn($action) => $permittedActions->get($action))
            ->isNotEmpty();
    }

    // check model is permitted all
    public function isPermittedAll(...$actions)
    {
        $permittedActions = $this->getPermittedActions();

        return collect($actions)
            ->filter(fn($action) => !$permittedActions->get($action))
            ->isEmpty();
    }

    // get permitted actions
    public function getPermittedActions() : mixed
    {
        return cache()->remember($this->getPermissionsCacheKey(), now()->addDays(7), function() {
            $actions = collect();

            foreach (model('permission')->actions() as $module => $operations) {
                foreach ($operations as $operation) {
                    $actions->put("$module.$operation", false);
                }
            }

            $actions = $actions->mapWithKeys(fn($val, $key) => [
                $key => $this->permissions()->where('permission', $key)->count() > 0,
            ]);

            return $actions;
        });
    }

    // get permissions cache key
    public function getPermissionsCacheKey() : string
    {
        return 'user_permissions_'.$this->id;
    }

    // clear permissions cache
    public function clearPermissionsCache() : void
    {
        cache()->forget($this->getPermissionsCacheKey());
    }

    // grant permission
    public function grantPermission($module, $action) : void
    {
        $key = "$module.$action";
        if ($this->permissions()->where('permission', $key)->count()) return;
        $this->permissions()->create(['permission' => $key]);
        $this->touch();
    }

    // forbid permission
    public function forbidPermission($module, $action) : void
    {
        $key = "$module.$action";
        $this->permissions()->where('permission', $key)->delete();
        $this->touch();
    }
}