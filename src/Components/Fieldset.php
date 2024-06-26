<?php

namespace Jiannius\Atom\Components;

use Illuminate\View\Component;

class Fieldset extends Component
{
    public function render()
    {
        return view('atom::components.fieldset');
    }
}