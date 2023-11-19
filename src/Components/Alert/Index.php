<?php

namespace Jiannius\Atom\Components\Alert;

use Illuminate\View\Component;

class Index extends Component
{
    public function render()
    {
        return view('atom::components.alert.index');
    }
}