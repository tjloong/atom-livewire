<?php

namespace Jiannius\Atom\Components;

use Illuminate\View\Component;

class CdnScripts extends Component
{
    public function render()
    {
        return view('atom::components.cdn-scripts');
    }
}