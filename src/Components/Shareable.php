<?php

namespace Jiannius\Atom\Components;

use Illuminate\View\Component;

class Shareable extends Component
{
    /**
     * Render
     */
    public function render()
    {
        return view('atom::components.shareable');
    }
}