<?php

namespace Jiannius\Atom\Components\Navbar\Mobile;

use Illuminate\View\Component;

class Item extends Component
{
    /**
     * Render
     */
    public function render()
    {
        return view('atom::components.navbar.mobile.item');
    }
}