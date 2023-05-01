<?php

namespace Jiannius\Atom\Http\Livewire\App\Permission;

use Livewire\Component;

class Form extends Component
{
    public $user;

    /**
     * Get query property
     */
    public function getQueryProperty(): mixed
    {
        return model('permission')
            ->where('user_id', $this->user->id)
            ->when(tenant(), fn($q) => $q->where('tenant_id', tenant('id')));
    }

    /**
     * Get permissions property
     */
    public function getPermissionsProperty(): array
    {
        $permissions = [];

        foreach (model('permission')->getPermissionList() as $module => $actions) {
            $permissions[$module] = collect($actions)->mapWithKeys(function($action) use ($module) {
                $permission = $module.'.'.$action;
                $forbidden = (clone $this->query)->where('permission', $permission)->where('is_granted', false)->count() > 0;
                $granted = (clone $this->query)->where('permission', $permission)->where('is_granted', true)->count() > 0;

                return [$action => $forbidden ? false : $granted];
            });
        }

        return $permissions;
    }

    /**
     * Toggle
     */
    public function toggle($permission, $granted): void
    {
        (clone $this->query)->where('permission', $permission)->delete();

        model('permission')->create(array_merge(
            [
                'permission' => $permission,
                'is_granted' => $granted,
                'user_id' => $this->user->id,
            ],

            tenant() ? ['tenant_id' => tenant('id')] : [],
        ));
    }

    /**
     * Render
     */
    public function render(): mixed
    {
        return atom_view('app.permission.form');
    }
}