<?php

namespace Jiannius\Atom\Components\Script;

use Illuminate\View\Component;

class Vendor extends Component
{
    /**
     * Render
     */
    public function render()
    {
        return view('atom::components.script.vendor');
    }
}