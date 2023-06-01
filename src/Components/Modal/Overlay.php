<?php

namespace Jiannius\Atom\Components\Modal;

use Illuminate\View\Component;

class Overlay extends Component
{
    /**
     * Render
     */
    public function render()
    {
        return view('atom::components.modal.overlay');
    }
}