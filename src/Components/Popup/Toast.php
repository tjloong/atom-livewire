<?php

namespace Jiannius\Atom\Components\Popup;

use Illuminate\View\Component;

class Toast extends Component
{
    /**
     * Render
     */
    public function render()
    {
        return view('atom::components.popup.toast');
    }
}