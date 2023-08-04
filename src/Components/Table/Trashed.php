<?php

namespace Jiannius\Atom\Components\Table;

use Illuminate\View\Component;

class Trashed extends Component
{
    public function render()
    {
        return view('atom::components.table.trashed');
    }
}