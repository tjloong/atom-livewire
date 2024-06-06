<?php

namespace Jiannius\Atom\Components;

use Illuminate\View\Component;

class Share extends Component
{
    public function render()
    {
        return view('atom::components.share');
    }
}