<?php

namespace Jiannius\Atom\Components\Button;

use Illuminate\View\Component;

class Submit extends Component
{
    /**
     * Render
     */
    public function render()
    {
        return view('atom::components.button.submit');
    }
}