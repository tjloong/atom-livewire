<?php

namespace Jiannius\Atom\Components;

use Illuminate\View\Component;

class Select extends Component
{
    public function render()
    {
        return view('atom::components.select');
    }
}