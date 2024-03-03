<?php

namespace Jiannius\Atom\Components;

use Illuminate\View\Component;

class MediaObject extends Component
{
    public function render()
    {
        return view('atom::components.media-object');
    }
}