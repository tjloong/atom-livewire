<?php

namespace Jiannius\Atom\Http\Livewire\App\User;

use Livewire\Component;

class BtnBlock extends Component
{
    public $user;

    /**
     * Block
     */
    public function block(): mixed
    {
        $this->user->block();

        return breadcrumbs()->back();
    }

    /**
     * Unblock
     */
    public function unblock(): mixed
    {
        $this->user->unblock();
        
        return breadcrumbs()->back();
    }

    /**
     * Render
     */
    public function render(): mixed
    {
        return atom_view('app.user.btn-block');
    }
}