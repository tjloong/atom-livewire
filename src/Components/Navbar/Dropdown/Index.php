<?php

namespace Jiannius\Atom\Components\Navbar\Dropdown;

use Illuminate\View\Component;

class Index extends Component
{
    /**
     * Contructor
     */
    public function __construct()
    {
        //
    }

    /**
     * Render
     */
    public function render()
    {
        return view('atom::components.navbar.dropdown.index');
    }
}