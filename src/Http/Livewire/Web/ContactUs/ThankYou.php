<?php

namespace Jiannius\Atom\Http\Livewire\Web\ContactUs;

use Livewire\Component;

class ThankYou extends Component
{
    /**
     * Render
     */
    public function render()
    {
        return view('atom::web.contact-us.thank-you')->layout('layouts.web');
    }
}