<?php

namespace Jiannius\Atom\Components;

use Illuminate\View\Component;

class Statsbox extends Component
{
    /**
     * Render
     */
    public function render()
    {
        return view('atom::components.statsbox');
    }
}