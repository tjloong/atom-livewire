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
     * Render component
     * 
     * @return void
     */
    public function render()
    {
        return view('atom::web.home')->layout('layouts.web');
    }
}