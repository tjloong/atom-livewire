<?php

namespace Jiannius\Atom\Components\Layout\App;

use Illuminate\View\Component;

class Layer extends Component
{
    public function render()
    {
        return view('atom::components.layout.app.layer');
    }
}