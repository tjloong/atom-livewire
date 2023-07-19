<?php

namespace Jiannius\Atom\Http\Livewire\Auth;

use Livewire\Component;

class SendVerification extends Component
{
    // mount
    public function mount(): void
    {
        user()->sendEmailVerificationNotification();
    }

    // render
    public function render()
    {
        return atom_view('auth.send-verification');
    }
}