<?php

namespace Jiannius\Atom\Components\Button;

use Illuminate\View\Component;

class Confirm extends Component
{
    /**
     * Render
     */
    public function render()
    {
        return view('atom::components.button.confirm');
    }
}