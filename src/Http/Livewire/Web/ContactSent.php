<?php

namespace Jiannius\Atom\Http\Livewire\Web;

use Livewire\Component;

class ContactSent extends Component
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
        return view('atom::web.contact-sent')->layout('layouts.web');
    }
}