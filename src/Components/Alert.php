<?php

namespace Jiannius\Atom\Components;

use Illuminate\View\Component;

class Alert extends Component
{
    /**
     * Render
     */
    public function render()
    {
        return view('atom::components.alert');
    }
}