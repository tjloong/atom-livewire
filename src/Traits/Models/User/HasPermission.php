<?php

namespace Jiannius\Atom\Traits\Models\User;

trait HasPermission
{
    // get permissions for user
    public function permissions(): mixed
    {
        return $this->hasMany(model('permission'));
    }

    // is permitted
    public function isPermitted($names, $strict = false): bool
    {
        if (session('permissions')) $perms = session('permissions');
        else {
            $perms = $this->permissions()->readable()->get()->pluck('name')->toArray();
            session(['permissions' => $perms]);
        }

        return $strict
            ? !collect($names)->contains(fn($name) => !in_array($name, $perms))
            : collect($names)->contains(fn($name) => in_array($name, $perms));
    }
}