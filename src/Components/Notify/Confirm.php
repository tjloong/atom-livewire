<?php

namespace Jiannius\Atom\Components\Notify;

use Illuminate\View\Component;

class Confirm extends Component
{
    public function render()
    {
        return view('atom::components.notify.confirm');
    }
}