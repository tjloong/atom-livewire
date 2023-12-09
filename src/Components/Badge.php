<?php

namespace Jiannius\Atom\Components;

use Illuminate\View\Component;

class Badge extends Component
{
    public function render()
    {
        return view('atom::components.badge');
    }
}