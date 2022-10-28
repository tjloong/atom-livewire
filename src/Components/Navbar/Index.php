<?php

namespace Jiannius\Atom\Components\Navbar;

use Illuminate\View\Component;

class Index extends Component
{
    /**
     * Render component
     */
    public function render()
    {
        return view('atom::components.navbar.index');
    }
}