<?php

namespace Jiannius\Atom\Components;

use Illuminate\View\Component;

class Modal extends Component
{
    public function render()
    {
        return view('atom::components.modal');
    }
}