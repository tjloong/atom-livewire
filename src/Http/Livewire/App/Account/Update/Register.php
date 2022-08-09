<?php

namespace Jiannius\Atom\Http\Livewire\App\Account\Update;

use Livewire\Component;

class Register extends Component
{
    public $account;

    /**
     * Get user property
     */
    public function getUserProperty()
    {
        return $this->account->users()->orderBy('id')->first();
    }
    
    /**
     * Render
     */
    public function render()
    {
        return view('atom::app.account.update.register');
    }
}