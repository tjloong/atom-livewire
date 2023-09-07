<?php

namespace Jiannius\Atom\Components\Table;

use Illuminate\View\Component;

class Archived extends Component
{
    public function render()
    {
        return view('atom::components.table.archived');
    }
}