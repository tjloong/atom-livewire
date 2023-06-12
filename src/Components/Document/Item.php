<?php

namespace Jiannius\Atom\Components\Document;

use Illuminate\View\Component;

class Item extends Component
{
    /**
     * Render
     */
    public function render()
    {
        return view('atom::components.document.item');
    }
}