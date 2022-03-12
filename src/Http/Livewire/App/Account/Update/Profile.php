<?php

namespace Jiannius\Atom\Http\Livewire\App\Account\Update;

use Livewire\Component;

class Profile extends Component
{
    public $user;
    
    /**
     * Block
     */
    public function block()
    {
        $this->user->account->block();
        $this->dispatchBrowserEvent('toast', ['message' => 'Account Blocked']);
    }

    /**
     * Unblock
     */
    public function unblock()
    {
        $this->user->account->unblock();
        $this->dispatchBrowserEvent('toast', ['message' => 'Account Unblocked']);
    }

    /**
     * Render
     */
    public function render()
    {
        return view('atom::app.account.update.profile');
    }
}