<?php

namespace Jiannius\Atom\Http\Livewire\Auth;

use Jiannius\Atom\Component;

class Logout extends Component
{
    // mount
    public function mount()
    {
        if ($mask = session('mask')) {
            auth()->logout();
            auth()->login($mask);

            session()->forget('mask');

            return redirect($mask->home());
        }
        else {
            auth()->logout();
            request()->session()->invalidate();
            request()->session()->regenerateToken();
    
            return redirect('/');
        }
    }

    // render
    public function render()
    {
        $this->skipRender();
    }
}