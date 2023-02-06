<?php

namespace Jiannius\Atom\Http\Livewire\App\User\Update;

use Livewire\Component;

class Block extends Component
{
    public $user;

    /**
     * Block
     */
    public function block()
    {
        $this->user->block();

        return breadcrumbs()->back();
    }

    /**
     * Unblock
     */
    public function unblock()
    {
        $this->user->unblock();
        
        return breadcrumbs()->back();
    }

    /**
     * Render
     */
    public function render()
    {
        return atom_view('app.user.update.block');
    }
}