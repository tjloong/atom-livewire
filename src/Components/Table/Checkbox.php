<?php

namespace Jiannius\Atom\Components\Table;

use Illuminate\View\Component;

class Checkbox extends Component
{
    /**
     * Render
     */
    public function render()
    {
        return view('atom::components.table.checkbox');
    }
}