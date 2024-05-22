<?php

namespace Jiannius\Atom\Components\Modal;

use Illuminate\View\Component;

class Page extends Component
{
    public function render()
    {
        return view('atom::components.modal.page');
    }
}