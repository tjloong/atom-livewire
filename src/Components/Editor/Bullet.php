<?php

namespace Jiannius\Atom\Components\Editor;

use Illuminate\View\Component;

class Bullet extends Component
{
    public function render()
    {
        return view('atom::components.editor.bullet');
    }
}