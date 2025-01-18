<?php

namespace Jiannius\Atom\Livewire\Auth;

use Jiannius\Atom\Atom;
use Jiannius\Atom\Traits\Livewire\AtomComponent;
use Livewire\Component;

class Logout extends Component
{
    use AtomComponent;

    public function mount()
    {
        return Atom::action('logout');
    }

    public function render()
    {
        $this->skipRender();
    }
}