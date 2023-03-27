<?php

namespace Jiannius\Atom\Components;

use Illuminate\View\Component;

class Link extends Component
{
    /**
     * Render
     */
    public function render()
    {
        return view('atom::components.link');
    }
}