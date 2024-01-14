<?php

namespace Jiannius\Atom\Components;

use Illuminate\View\Component;

class FileCard extends Component
{
    public function render() : mixed
    {
        return view('atom::components.file-card');
    }
}