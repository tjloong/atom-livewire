<?php

namespace Jiannius\Atom\Components\Notify;

use Illuminate\View\Component;

class Toast extends Component
{
    public function render()
    {
        return view('atom::components.notify.toast');
    }
}