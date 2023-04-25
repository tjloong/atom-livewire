<?php

namespace Jiannius\Atom\Components;

use Illuminate\View\Component;

class FlipCountdown extends Component
{
    /**
     * Render
     */
    public function render()
    {
        return view('atom::components.flip-countdown');
    }
}