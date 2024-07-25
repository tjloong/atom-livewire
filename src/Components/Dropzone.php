<?php

namespace Jiannius\Atom\Components;

use Illuminate\View\Component;

class Dropzone extends Component
{
    public function render()
    {
        return view('atom::components.dropzone');
    }
}