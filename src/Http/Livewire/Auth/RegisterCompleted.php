<?php

namespace Jiannius\Atom\Http\Livewire\Auth;

use Livewire\Component;

class RegisterCompleted extends Component
{
    /**
     * Mount
     * 
     * @return void
     */
    public function mount()
    {
        //
    }

    /**
     * Rendering livewire view
     * 
     * @return Response
     */
    public function render()
    {
        return view('atom::auth.register-completed')->layout('layouts.auth');
    }
}