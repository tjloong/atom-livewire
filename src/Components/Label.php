<?php

namespace Jiannius\Atom\Components;

use Illuminate\View\Component;

class Label extends Component
{
    public function render() : mixed
    {
        return view('atom::components.label');
    }
}