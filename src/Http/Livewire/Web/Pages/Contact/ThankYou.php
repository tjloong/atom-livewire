<?php

namespace Jiannius\Atom\Http\Livewire\Web\Pages\Contact;

use Livewire\Component;

class ThankYou extends Component
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
        return view('atom::web.pages.contact.thank-you')->layout('layouts.web');
    }
}