<?php

namespace Jiannius\Atom\Components;

use Illuminate\View\Component;

class HtmlMeta extends Component
{
    public function render()
    {
        return view('atom::components.html-meta');
    }
}