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
    public function open($id): void
    {
        $this->signup = model('signup')->find($id);
        $this->dispatchBrowserEvent('signup-update-open');
    }

    // close
    public function close(): void
    {
        $this->dispatchBrowserEvent('signup-update-close');
    }
}