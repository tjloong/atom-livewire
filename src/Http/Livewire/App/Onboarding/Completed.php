<?php

namespace Jiannius\Atom\Http\Livewire\App\Onboarding;

use Livewire\Component;

class Completed extends Component
{
    /**
     * Mount
     */
    public function mount()
    {
        auth()->user()->account->onboard();
    }

    /**
     * Render
     */
    public function render()
    {
        return atom_view('app.onboarding.completed');
    }
}