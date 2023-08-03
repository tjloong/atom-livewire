<?php

namespace Jiannius\Atom\Components\Button;

use Illuminate\View\Component;

class Delete extends Component
{
    public function render()
    {
        return view('atom::components.button.delete');
    }
}