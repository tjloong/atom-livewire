<?php

namespace Jiannius\Atom\Components\Tabs;

use Illuminate\View\Component;

class Tab extends Component
{
    public function render()
    {
        return view('atom::components.tabs.tab');
    }
}