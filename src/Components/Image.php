<?php

namespace Jiannius\Atom\Components;

use Illuminate\View\Component;

class Image extends Component
{
    public function render()
    {
        return view('atom::components.image');
    }
}