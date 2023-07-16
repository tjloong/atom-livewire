<?php

namespace Jiannius\Atom\Components\Sortable;

use Illuminate\View\Component;

class Index extends Component
{
    public function render()
    {
        return view('atom::components.sortable.index');
    }
}