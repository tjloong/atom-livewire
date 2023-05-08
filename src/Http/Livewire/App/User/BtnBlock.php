<?php

namespace Jiannius\Atom\Http\Livewire\App\User;

use Jiannius\Atom\Traits\Livewire\WithPopupNotify;
use Livewire\Component;

class BtnBlock extends Component
{
    use WithPopupNotify;

    public $user;

    /**
     * Block
     */
    public function block(): mixed
    {
        if ($this->user->id === user('id')) {
            return $this->popup([
                'title' => 'Unable To Block User',
                'message' => 'You cannot block yourself.',
            ], 'alert', 'error');
        }

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