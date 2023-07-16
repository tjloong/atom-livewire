<?php

namespace Jiannius\Atom\Components\Sortable;

use Illuminate\View\Component;

class Item extends Component
{
    public function render()
    {
        return view('atom::components.sortable.item');
    }
}