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
}