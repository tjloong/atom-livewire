<?php

namespace Jiannius\Atom\Components\Slider;

use Illuminate\View\Component;

class Thumbs extends Component
{
    /**
     * Render
     */
    public function render()
    {
        return view('atom::components.slider.thumbs');
    }
}