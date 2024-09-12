<?php

namespace Jiannius\Atom\Components\Editor\Menu;

use Illuminate\View\Component;

class Link extends Component
{
    public function render()
    {
        return view('atom::components.editor.menu.link');
    }
}