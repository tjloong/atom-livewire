<?php

namespace Jiannius\Atom\Components;

use Illuminate\View\Component;

class FileInput extends Component
{
    public function render()
    {
        return view('atom::components.file-input');
    }
}