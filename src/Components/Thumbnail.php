<?php

namespace Jiannius\Atom\Components;

use Illuminate\View\Component;

class Thumbnail extends Component
{
    /**
     * Render
     */
    public function render()
    {
        return view('atom::components.thumbnail');
    }
}