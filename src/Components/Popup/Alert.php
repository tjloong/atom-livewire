<?php

namespace Jiannius\Atom\Components\Popup;

use Illuminate\View\Component;

class Alert extends Component
{
    /**
     * Render
     */
    public function render()
    {
        return view('atom::components.popup.alert');
    }
}