<?php

namespace Jiannius\Atom\Http\Livewire\Web;

use Livewire\Component;

class Home extends Component
{
    /**
     * Mount
     * 
     * @return void
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
        return model('plan')->where('is_active', true)->get();
    }

    /**
     * Render component
     * 
     * @return void
     */
    public function render()
    {
        return view('atom::web.home', [
            'plans' => $this->plans,
        ])->layout('layouts.web');
    }
}