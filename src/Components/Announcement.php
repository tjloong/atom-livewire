<?php

namespace Jiannius\Atom\Components;

use Illuminate\View\Component;

class Announcement extends Component
{
    public function render() : mixed
    {
        return view('atom::components.announcement');
    }
}