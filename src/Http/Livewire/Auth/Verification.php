<?php

namespace Jiannius\Atom\Http\Livewire\Auth;

use Jiannius\Atom\Component;

class Verification extends Component
{
    // mount
    public function mount(\Illuminate\Foundation\Auth\EmailVerificationRequest $request = null): void
    {
        $request->fulfill();
    }
}