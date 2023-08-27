<?php

namespace Jiannius\Atom\Components\Table;

use Illuminate\View\Component;

class Heading extends Component
{
    public function render()
    {
        return view('atom::components.table.heading');
    }
}