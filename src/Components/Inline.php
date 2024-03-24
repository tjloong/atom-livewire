<?php

namespace Jiannius\Atom\Components;

use Illuminate\View\Component;

class Inline extends Component
{
    public function render()
    {
        return view('atom::components.inline');
    }
}