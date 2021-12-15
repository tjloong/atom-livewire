<?php

namespace App\Http\Livewire\Web;

use Livewire\Component;

class Home extends Component
{
    /**
     * Mount event
     * 
     * @return void
     */
    public function mount()
    {
        //
    }

    /**
     * Rendering livewire view
     * 
     * @return Response
     */
    public function render()
    {
        return view('livewire.web.home')->layout('layouts.web');
    }
}