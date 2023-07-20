<?php

namespace Jiannius\Atom\Http\Livewire\Auth;

use Jiannius\Atom\Component;

class SendVerification extends Component
{
    // mount
    public function mount(): void
    {
        user()->sendEmailVerificationNotification();
    }
}