<?php

namespace Jiannius\Atom\Http\Livewire\App\Settings\User;

use Jiannius\Atom\Traits\Livewire\WithPopupNotify;
use Livewire\Component;

class Permission extends Component
{
    use WithPopupNotify;
    
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
                if (tenant() && $this->user->isTenantOwner()) return [$action => true];
                else {
                    $permission = $module.'.'.$action;
                    $forbidden = (clone $this->query)->where('permission', $permission)->where('is_granted', false)->count() > 0;
                    $granted = (clone $this->query)->where('permission', $permission)->where('is_granted', true)->count() > 0;
    
                    return [$action => $forbidden ? false : $granted];
                }
            });
        }

        return $permissions;
    }

    /**
     * Toggle
     */
    public function toggle($permission, $granted): void
    {
        if (tenant() && $this->user->isTenantOwner()) {
            $this->popup([
                'title' => 'Owner Permissions',
                'message' => 'Owner always has all permissions.',
            ], 'alert');
        }
        else {
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
    }

    /**
     * Render
     */
    public function render(): mixed
    {
        return atom_view('app.settings.user.permission');
    }
}