<?php

namespace Jiannius\Atom\Components\Lightbox;

use Illuminate\View\Component;

class Slide extends Component
{
    /**
     * Render
     */
    public function render()
    {
        return view('atom::components.lightbox.slide');
    }
}