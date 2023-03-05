<?php

namespace Jiannius\Atom\Components;

use Illuminate\View\Component;

class Field extends Component
{
    /**
     * Render
     */
    public function render()
    {
        return view('atom::components.field');
    }
}