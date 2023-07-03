<?php

namespace Jiannius\Atom\Components\PageHeader;

use Illuminate\View\Component;

class Index extends Component
{
    // render
    public function render()
    {
        return view('atom::components.page-header.index');
    }
}