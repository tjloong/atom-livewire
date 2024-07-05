<?php

namespace Jiannius\Atom\Components;

use Illuminate\View\Component;

class Dialog extends Component
{
    public function render() : mixed
    {
        return view('atom::components.dialog');
    }
}