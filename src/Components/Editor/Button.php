<?php

namespace Jiannius\Atom\Components\Editor;

use Illuminate\View\Component;

class Button extends Component
{
    public function render()
    {
        return view('atom::components.editor.button');
    }
}