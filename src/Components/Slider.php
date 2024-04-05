<?php

namespace Jiannius\Atom\Components;

use Illuminate\View\Component;

class Slider extends Component
{
    public function render() : mixed
    {
        return view('atom::components.slider');
    }
}