<?php

namespace Jiannius\Atom\Components\Document;

use Illuminate\View\Component;

class Total extends Component
{
    /**
     * Render
     */
    public function render()
    {
        return view('atom::components.document.total');
    }
}