<?php

namespace Jiannius\Atom\Components;

use Illuminate\View\Component;

class Plan extends Component
{
    /**
     * Render
     */
    public function render()
    {
        return view('atom::components.plan');
    }
}