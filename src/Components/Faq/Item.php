<?php

namespace Jiannius\Atom\Components\Faq;

use Illuminate\View\Component;

class Item extends Component
{
    /**
     * Render
     */
    public function render()
    {
        return view('atom::components.faq.item');
    }
}