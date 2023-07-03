<?php

namespace Jiannius\Atom\Components;

use Illuminate\View\Component;

class Breadcrumbs extends Component
{
    // render
    public function render()
    {
        return view('atom::components.breadcrumbs');
    }
}