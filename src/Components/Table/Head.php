<?php

namespace Jiannius\Atom\Components\Table;

use Illuminate\View\Component;

class Head extends Component
{
    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\View\View|\Closure|string
     */
    public function render()
    {
        return view('atom::components.table.head');
    }
}