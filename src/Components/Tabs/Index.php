<?php

namespace Jiannius\Atom\Components\Tabs;

use Illuminate\View\Component;

class Index extends Component
{
    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\View\View|\Closure|string
     */
    public function render()
    {
        return view('atom::components.tabs.index');
    }
}