<?php

namespace Jiannius\Atom\Components\Navbar;

use Illuminate\View\Component;

class Auth extends Component
{
    public function render()
    {
        return view('atom::components.navbar.auth');
    }
}