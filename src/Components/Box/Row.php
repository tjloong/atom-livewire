<?php

namespace Jiannius\Atom\Components\Box;

use Illuminate\View\Component;

class Row extends Component
{
    /**
     * Render
     */
    public function render()
    {
        return view('atom::components.box.row');
    }
}