<?php

namespace Jiannius\Atom\Http\Livewire\Onboarding;

use Livewire\Component;

class Completed extends Component
{
    /**
     * Mount
     */
    public function mount()
    {
        //
    }

    /**
     * Render
     */
    public function render()
    {
        return view('atom::onboarding.completed')->layout('layouts.onboarding');
    }
}