<?php

namespace Jiannius\Atom\Http\Livewire\App\Signup;

use Jiannius\Atom\Component;

class Update extends Component
{
    public $signup;

    protected $listeners = [
        'updateSignup' => 'open',
    ];

    // open
    public function open($id) : void
    {
        if ($this->signup = model('signup')->find($id)) {
            $this->openDrawer('signup-update');
        }
    }

    // close
    public function close() : void
    {
        $this->closeDrawer('signup-update');
    }
}