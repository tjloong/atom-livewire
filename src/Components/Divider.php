<?php

namespace Jiannius\Atom\Components;

use Illuminate\View\Component;

class Divider extends Component
{
    public function render() : mixed
    {
        return view('atom::components.divider');
    }
}