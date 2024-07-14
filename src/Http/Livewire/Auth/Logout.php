<?php

namespace Jiannius\Atom\Http\Livewire\Auth;

use Jiannius\Atom\Component;

class Logout extends Component
{
    // mount
    public function mount()
    {
        auth()->logout();
        request()->session()->flush();
        request()->session()->invalidate();
        request()->session()->regenerateToken();

        return redirect('/');
    }

    // render
    public function render()
    {
        $this->skipRender();
    }
}