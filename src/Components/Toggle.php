<?php

namespace Jiannius\Atom\Components;

use Illuminate\View\Component;

class Toggle extends Component
{
    public function render()
    {
        return view('atom::components.toggle');
    }
}