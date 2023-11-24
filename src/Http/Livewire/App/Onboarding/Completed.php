<?php

namespace Jiannius\Atom\Http\Livewire\App\Onboarding;

use Jiannius\Atom\Component;

class Completed extends Component
{
    public $redirect;

    public $queryString = ['redirect'];

    // mount
    public function mount() : void
    {
        if (user()->signup && !user()->signup->onboarded_at) {
            user()->signup->fill(['onboarded_at' => now()])->save();
        }

        session()->forget('onboarding');
    }
}