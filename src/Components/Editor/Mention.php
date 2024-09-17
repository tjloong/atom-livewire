<?php

namespace Jiannius\Atom\Components\Editor;

use Illuminate\View\Component;

class Mention extends Component
{
    public function render()
    {
        return view('atom::components.editor.mention');
    }
}