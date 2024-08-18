<?php

namespace Jiannius\Atom\Components;

use Illuminate\View\Component;

class TagInput extends Component
{
    public function render()
    {
        return view('atom::components.tag-input');
    }
}