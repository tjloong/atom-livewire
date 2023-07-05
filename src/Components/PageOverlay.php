<?php

namespace Jiannius\Atom\Components;

use Illuminate\View\Component;

class PageOverlay extends Component
{
    public function render()
    {
        return view('atom::components.page-overlay');
    }
}