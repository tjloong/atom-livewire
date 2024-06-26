<?php

namespace Jiannius\Atom\Components;

use Illuminate\View\Component;

class Carbon extends Component
{
    public function render()
    {
        return view('atom::components.carbon');
    }
}