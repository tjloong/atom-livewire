<?php

namespace Jiannius\Atom\Components\Dropdown;

use Illuminate\View\Component;

class Delete extends Component
{
    public function render()
    {
        return view('atom::components.dropdown.delete');
    }
}