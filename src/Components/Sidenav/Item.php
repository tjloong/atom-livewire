<?php

namespace Jiannius\Atom\Components\Sidenav;

use Illuminate\View\Component;

class Item extends Component
{
    /**
     * Render
     */
    public function render()
    {
        return view('atom::components.sidenav.item');
    }
}