<?php

namespace Jiannius\Atom\Components;

use Illuminate\View\Component;

class Popup extends Component
{
    public function render() : mixed
    {
        return view('atom::components.popup');
    }
}