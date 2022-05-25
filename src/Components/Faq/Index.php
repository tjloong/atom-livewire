<?php

namespace Jiannius\Atom\Components\Faq;

use Illuminate\View\Component;

class Index extends Component
{
    /**
     * Render
     */
    public function render()
    {
        return view('atom::components.faq.index');
    }
}