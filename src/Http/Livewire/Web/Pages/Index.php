<?php

namespace Jiannius\Atom\Http\Livewire\Web\Pages;

use Livewire\Component;

class Index extends Component
{
    /**
     * Mount
     */
    public function mount()
    {
        //
    }

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
        return view('atom::web.pages.index', [
            'plans' => $this->plans,
        ])->layout('layouts.web');
    }
}