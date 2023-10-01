<?php

namespace Jiannius\Atom\Components;

use Illuminate\View\Component;

class UserCard extends Component
{
    public function render()
    {
        return view('atom::components.user-card');
    }
}