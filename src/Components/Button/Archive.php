<?php

namespace Jiannius\Atom\Components\Button;

use Illuminate\View\Component;

class Archive extends Component
{
    public function render()
    {
        return view('atom::components.button.archive');
    }
}