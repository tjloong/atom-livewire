<?php

namespace Jiannius\Atom\Components;

use Illuminate\View\Component;

class Drawer extends Component
{
    public function render() : mixed
    {
        return view('atom::components.drawer');
    }
}