<?php

namespace Jiannius\Atom\Components\Table;

use Illuminate\View\Component;

class Td extends Component
{
    public function render()
    {
        return view('atom::components.table.td');
    }
}