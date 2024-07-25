<?php

namespace Jiannius\Atom\Components;

use Illuminate\View\Component;

class File extends Component
{
    public function render() : mixed
    {
        return view('atom::components.file');
    }
}