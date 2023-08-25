<?php

namespace Jiannius\Atom\Components;

use Illuminate\View\Component;

class Heading extends Component
{
    public function render()
    {
        return view('atom::components.heading');
    }
}