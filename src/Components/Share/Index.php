<?php

namespace Jiannius\Atom\Components\Share;

use Illuminate\View\Component;

class Index extends Component
{
    public function render()
    {
        return view('atom::components.share.index');
    }
}