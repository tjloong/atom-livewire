<?php

namespace Jiannius\Atom\Components;

use Illuminate\View\Component;

class DatetimePicker extends Component
{
    public function render()
    {
        return view('atom::components.datetime-picker');
    }
}