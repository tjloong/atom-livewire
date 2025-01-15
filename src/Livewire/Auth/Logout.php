<?php

namespace Jiannius\Atom\Livewire\Auth;

use Jiannius\Atom\Traits\Livewire\AtomComponent;
use Livewire\Component;

class Logout extends Component
{
    use AtomComponent;

    public function mount()
    {
        auth()->logout();
        request()->session()->flush();
        request()->session()->invalidate();
        request()->session()->regenerateToken();

        return redirect('/');
    }

    public function render()
    {
        $this->skipRender();
    }
}