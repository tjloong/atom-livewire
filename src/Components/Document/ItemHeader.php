<?php

namespace Jiannius\Atom\Components\Document;

use Illuminate\View\Component;

class ItemHeader extends Component
{
    /**
     * Render
     */
    public function render()
    {
        return view('atom::components.document.item-header');
    }
}