<?php

namespace Jiannius\Atom\Http\Livewire\Web;

use Livewire\Component;

class Index extends Component
{
    /**
     * Get plans property
     */
    public function getPlansProperty()
    {
        if (!enabled_module('plans')) return;
        
        return model('plan')->where('is_active', true)->get();
    }

    /**
     * Render
     */
    public function render()
    {
        return atom_view('web');
    }
}