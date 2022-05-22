<?php

namespace Jiannius\Atom\Components\Navbar;

use Illuminate\View\Component;

class Locale extends Component
{
    /**
     * Render
     */
    public function render()
    {
        return view('atom::components.navbar.locale');
    }
}