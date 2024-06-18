<?php

namespace Jiannius\Atom\Components;

use Illuminate\View\Component;

class Stat extends Component
{
    public function render()
    {
        return view('atom::components.stat');
    }
}