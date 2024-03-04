<?php

namespace Jiannius\Atom\Traits\Models;

use Illuminate\Database\Eloquent\Relations\HasMany;

// this trait should be consumed by user model
trait Permissions
{
    // get permissions for model
    public function permissions() : HasMany
    {
        return $this->hasMany(model('permission'));
    }

    // check model is permitted
    public function isPermitted($actions)
    {
        if ($permissions = $this->cache('permissions', $this->permissions)) {
            return collect($actions)->contains(fn($action) => !empty($permissions->firstWhere('permission', $action)));
        }

        return false;
    }

    // check model is permitted all
    public function isPermittedAll($actions) : bool
    {
        if ($permissions = $this->cache('permissions', $this->permissions)) {
            return !collect($actions)->contains(fn($action) => empty($permissions->firstWhere('permission', $action)));
        }

        return false;
    }

    // get permissions list
    public function getPermissionsList() : array
    {
        return collect(model('permission')->permissions())->mapWithKeys(fn($actions, $module) => [
            $module => collect($actions)->mapWithKeys(fn($action) => [
                $action => $this->permissions()
                    ->where('permission', $module.'.'.$action)
                    ->count() > 0,
            ])
        ])->toArray();
    }

    // save permissions
    public function savePermissions($permissions) : void
    {
        foreach ($permissions as $module => $actions) {
            foreach ($actions as $action => $allow) {
                $key = $module.'.'.$action;

                if (!$allow) $this->permissions()->where('permission', $key)->delete();
                else if (!$this->permissions()->where('permission', $key)->count()) {
                    $this->permissions()->create(['permission' => $key]);
                }
            }
        }
    }
}