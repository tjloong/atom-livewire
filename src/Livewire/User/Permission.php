<?php

namespace Jiannius\Atom\Livewire\User;

use Jiannius\Atom\Traits\Livewire\AtomComponent;
use Livewire\Component;

class Permission extends Component
{
    use AtomComponent;

    public $user;

    public function getPermissionsProperty() : mixed
    {
        return collect(model('permission')->actions())->mapWithKeys(fn($actions, $module) => [
            $module => collect($actions)->mapWithKeys(fn($action) => [
                $action => $this->user->isPermitted("$module.$action"),
            ])
        ]);
    }

    public function toggle($module, $action) : void
    {
        if ($this->user->isPermitted("$module.$action")) {
            $this->user->forbidPermission($module, $action);
        }
        else $this->user->grantPermission($module, $action);
    }
}
