<?php

namespace Jiannius\Atom\Components\Table;

use Illuminate\View\Component;

class Index extends Component
{
    /**
     * Render
     */
    public function render()
    {
        return view('atom::components.table.index');
    }
}