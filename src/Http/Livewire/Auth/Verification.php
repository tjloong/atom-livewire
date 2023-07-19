<?php

namespace Jiannius\Atom\Http\Livewire\Auth;

use Livewire\Component;

class Verification extends Component
{
    // mount
    public function mount(\Illuminate\Foundation\Auth\EmailVerificationRequest $request): void
    {
        $request->fulfill();
    }

    // render
    public function render()
    {
        return atom_view('auth.verification');
    }
}