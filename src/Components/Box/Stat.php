<?php

namespace Jiannius\Atom\Components\Box;

use Illuminate\View\Component;

class Stat extends Component
{
    public function render()
    {
        return view('atom::components.box.stat');
    }
}