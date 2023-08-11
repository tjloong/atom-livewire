<?php

namespace Jiannius\Atom\Components\Dropdown;

use Illuminate\View\Component;

class Item extends Component
{
    public function render()
    {
        return view('atom::components.dropdown.item');
    }
}