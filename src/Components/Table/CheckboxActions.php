<?php

namespace Jiannius\Atom\Components\Table;

use Illuminate\View\Component;

class CheckboxActions extends Component
{
    public function render()
    {
        return view('atom::components.table.checkbox-actions');
    }
}