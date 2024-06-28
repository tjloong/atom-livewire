<?php

namespace Jiannius\Atom\Components;

use Illuminate\View\Component;

class TimePicker extends Component
{
    public function render()
    {
        return view('atom::components.time-picker');
    }
}