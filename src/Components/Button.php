<?php

namespace Jiannius\Atom\Components;

use Illuminate\View\Component;

class Button extends Component
{
    public function render()
    {
        return view('atom::components.button');
    }
}