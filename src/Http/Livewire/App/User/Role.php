<?php

namespace Jiannius\Atom\Http\Livewire\App\User;

use Jiannius\Atom\Traits\Livewire\WithPopupNotify;
use Livewire\Component;

class Role extends Component
{
    use WithPopupNotify;
    
    public $user;

    /**
     * Get roles property
     */
    public function getRolesProperty(): mixed
    {
        return model('role')->readable()->get();
    }

    /**
     * Render
     */
    public function render(): mixed
    {
        return atom_view('app.user.role');
    }
}