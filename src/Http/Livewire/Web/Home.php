<?php

namespace Jiannius\Atom\Http\Livewire\Web;

use Livewire\Component;

class Home extends Component
{
    /**
     * Get plans property
     */
    public function getPlansProperty()
    {
        if (!has_table('plans')) return;
        
        return model('plan')->where('is_active', true)->get();
    }

    /**
     * Render
     */
    public function render()
    {
        return atom_view('web.home');
    }
}