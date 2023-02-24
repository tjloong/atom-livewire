<?php

namespace Jiannius\Atom\Http\Livewire\App\Permission;

use Livewire\Component;

class Form extends Component
{
    public $user;
    public $role;

    /**
     * Get title property
     */
    public function getTitleProperty()
    {
        if ($this->user) return 'User Permissions';
        if ($this->role) return 'Role Permissions';
    }

    /**
     * Get permissions property
     */
    public function getPermissionsProperty()
    {
        $permissions = [];

        foreach (model('permission')->getActions() as $module => $actions) {
            $permissions[$module] = collect($actions)->map(function($action) use ($module) {
                $ability = $module.'.'.$action;
                $permission = ['name' => $action];

                if ($this->user) {
                    $permission['is_granted'] = $this->user->can($ability);
                    $permission['is_granted_by_role'] = enabled_module('roles')
                        && $this->user->role
                        && !$this->user->permissions()->where('permission', $ability)->count()
                        && $this->user->role->can($ability);
                }
                else if ($this->role) {
                    $permission['is_granted'] = $this->role->can($ability);
                }

                return $permission;
            });
        }

        return $permissions;
    }

    /**
     * Get is admin property
     */
    public function getIsAdminProperty()
    {
        return ($this->role ?? $this->user->role)->is_admin;
    }

    /**
     * Toggle
     */
    public function toggle($module, $action)
    {
        $ability = $module.'.'.$action;

        if ($this->user) {
            if (enabled_module('roles') && $this->user->role) {
                if ($this->user->permissions()->where('permission', $ability)->count()) {
                    $this->user->permissions()->where('permission', $ability)->delete();
                }
                else if ($rolePermission = $this->user->role->permissions()->where('permission', $ability)->first()) {
                    $this->user->permissions()->create([
                        'permission' => $ability,
                        'is_granted' => !$rolePermission->is_granted,
                    ]);
                }
                else {
                    $this->user->permissions()->create([
                        'permission' => $ability,
                        'is_granted' => true,
                    ]);
                }
            }
            else {
                if ($this->user->permissions()->granted($ability)->count()) {
                    $this->user->permissions()->where('permission', $ability)->delete();
                }
                else {
                    $permission = $this->user->permissions()->firstOrCreate(['permission' => $ability]);
                    $permission->is_granted = true;
                    $permission->save();
                }
            }
        }
        else if ($this->role) {
            $permission = $this->role->permissions()->firstOrNew(['permission' => $ability]);
            $permission->is_granted = !$permission->is_granted;
            $permission->save();
        }
    }

    /**
     * Render
     */
    public function render()
    {
        return atom_view('app.permission.form');
    }
}