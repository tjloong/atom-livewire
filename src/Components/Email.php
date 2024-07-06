<?php

namespace Jiannius\Atom\Components;

use Illuminate\View\Component;

class Email extends Component
{
    public function render()
    {
        return view('atom::components.email');
    }
}