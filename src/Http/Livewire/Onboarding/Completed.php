<?php

namespace Jiannius\Atom\Http\Livewire\Onboarding;

use Jiannius\Atom\Component;

class Completed extends Component
{
    public $redirect;

    // mount
    public function mount() : void
    {
        $this->redirect = request()->query('redirect');

        if (user()->signup && !user()->signup->onboarded_at) {
            user()->signup->fill(['onboarded_at' => now()])->save();
        }

        session()->forget('onboarding');
    }
}