<?php

namespace Jiannius\Atom\Http\Livewire\App\Signup;

use Livewire\Component;

class Edit extends Component
{
    public $signup;

    protected $listeners = [
        'editSignup' => 'open',
    ];

    // open
    public function open($id) : void
    {
        if ($this->signup = model('signup')->find($id)) {
            $this->overlay();
        }
    }
}