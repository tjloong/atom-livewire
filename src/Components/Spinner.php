<?php

namespace Jiannius\Atom\Components;

use Illuminate\View\Component;

class Spinner extends Component
{
    public function render()
    {
        return view('atom::components.spinner');
    }
}