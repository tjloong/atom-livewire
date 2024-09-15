<?php

namespace Jiannius\Atom\Components;

use Illuminate\View\Component;

class ColorPicker extends Component
{
    public function render()
    {
        return view('atom::components.color-picker');
    }
}