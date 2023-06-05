<?php

namespace Jiannius\Atom\Components\Alert;

use Illuminate\View\Component;

class Errors extends Component
{
    /**
     * Render
     */
    public function render()
    {
        return view('atom::components.alert.errors');
    }
}