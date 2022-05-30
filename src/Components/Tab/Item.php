<?php

namespace Jiannius\Atom\Components\Tab;

use Illuminate\View\Component;

class Item extends Component
{
    /**
     * Render
     */
    public function render()
    {
        return view('atom::components.tab.item');
    }
}