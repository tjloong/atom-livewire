<?php

namespace Jiannius\Atom\Components;

use Illuminate\View\Component;

class NoResult extends Component
{
    public function render()
    {
        return view('atom::components.no-result');
    }
}