<?php

namespace Jiannius\Atom\Components\Notify;

use Illuminate\View\Component;

class Alert extends Component
{
    public function render()
    {
        return view('atom::components.notify.alert');
    }
}