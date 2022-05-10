<?php

namespace Jiannius\Atom\Components;

use Illuminate\View\Component;

class Statbox extends Component
{
    /**
     * Render
     */
    public function render()
    {
        return view('atom::components.statbox');
    }
}