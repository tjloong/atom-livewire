<?php

namespace Jiannius\Atom\Components\PageHeader;

use Illuminate\View\Component;

class Back extends Component
{
    // render
    public function render()
    {
        return view('atom::components.page-header.back');
    }
}