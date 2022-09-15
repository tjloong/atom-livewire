<?php

namespace Jiannius\Atom\Components;

use Illuminate\View\Component;

class Badge extends Component
{
    /**
     * Render
     */
    public function render()
    {
        return view('atom::components.badge');
    }
}