<?php

namespace Jiannius\Atom\Components\Landing;

use Illuminate\View\Component;

class Popup extends Component
{
    /**
     * Render component
     */
    public function render()
    {
        return view('atom::components.landing.popup');
    }
}