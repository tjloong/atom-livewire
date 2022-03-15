<?php

namespace Jiannius\Atom\Http\Livewire\App\Account\Update;

use Livewire\Component;

class Overview extends Component
{
    public $user;
    
    /**
     * Render
     */
    public function render()
    {
        return view('atom::app.account.update.overview');
    }
}