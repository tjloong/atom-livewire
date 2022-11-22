<?php

namespace Jiannius\Atom\Http\Livewire\App\Settings\System;

use Livewire\Component;

class PermissionFormModal extends Component
{
    public $user;
    public $role;

    protected $listeners = ['open'];

    /**
     * Open
     */
    public function open($data)
    {
        if ($userId = data_get($data, 'user_id')) $this->user = model('user')->findOrFail($userId);
        if ($roleId = data_get($data, 'role_id')) $this->role = model('role')->findOrFail($roleId);

        $this->dispatchBrowserEvent('permission-form-modal-open');
    }

    /**
     * Get permissions property
     */
    public function getPermissionsProperty()
    {
        $permissions = [];

        foreach (
            config('atom.app.permissions.'.auth()->user()->account->type) ?? []
            as $module => $actions
        ) {
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
        return ($this->role->slug ?? $this->user->role->slug ?? null) === 'admin';
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

        $this->emitUp('refresh');
    }

    /**
     * Render
     */
    public function render()
    {
        return atom_view('app.settings.system.permission-form-modal');
    }
}