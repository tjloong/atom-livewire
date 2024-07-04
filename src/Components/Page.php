<?php

namespace Jiannius\Atom\Components;

use Illuminate\View\Component;

class Page extends Component
{
    public function render()
    {
        return view('atom::components.page');
    }
}