<?php

namespace Jiannius\Atom\Components\Layout;

use Illuminate\View\Component;

class Index extends Component
{
    public function render()
    {
        return view('atom::components.layout.index');
    }
}