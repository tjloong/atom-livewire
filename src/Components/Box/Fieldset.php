<?php

namespace Jiannius\Atom\Components\Box;

use Illuminate\View\Component;

class Fieldset extends Component
{
    // render
    public function render()
    {
        return view('atom::components.box.fieldset');
    }
}