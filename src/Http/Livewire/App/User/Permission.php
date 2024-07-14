<?php

namespace Jiannius\Atom\Http\Livewire\App\User;

use Jiannius\Atom\Component;

class Permission extends Component
{
    public $user;

    // get permissions property
    public function getPermissionsProperty() : mixed
    {
        return collect(model('permission')->actions())->mapWithKeys(fn($actions, $module) => [
            $module => collect($actions)->mapWithKeys(fn($action) => [
                $action => $this->user->isPermitted("$module.$action"),
            ])
        ]);
    }

    // toggle
    public function toggle($module, $action) : void
    {
        if ($this->user->isPermitted("$module.$action")) {
            $this->user->forbidPermission($module, $action);
        }
        else $this->user->grantPermission($module, $action);
    }
}
