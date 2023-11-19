<?php

namespace Jiannius\Atom\Components\AdminPanel;

use Illuminate\View\Component;

class Aside extends Component
{
    public function render()
    {
        return view('atom::components.admin-panel.aside');
    }
}