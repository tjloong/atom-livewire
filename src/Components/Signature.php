<?php

namespace Jiannius\Atom\Components;

use Illuminate\View\Component;

class Signature extends Component
{
    public function render()
    {
        return view('atom::components.signature');
    }
}