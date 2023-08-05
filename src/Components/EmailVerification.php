<?php

namespace Jiannius\Atom\Components;

use Illuminate\View\Component;

class EmailVerification extends Component
{
    public function render()
    {
        return view('atom::components.email-verification');
    }
}