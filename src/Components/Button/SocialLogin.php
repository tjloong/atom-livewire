<?php

namespace Jiannius\Atom\Components\Button;

use Illuminate\View\Component;

class SocialLogin extends Component
{
    public function render()
    {
        return view('atom::components.button.social-login');
    }
}