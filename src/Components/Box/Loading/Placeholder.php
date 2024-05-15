<?php

namespace Jiannius\Atom\Components\Box\Loading;

use Illuminate\View\Component;

class Placeholder extends Component
{
    public function render()
    {
        return view('atom::components.box.loading.placeholder');
    }
}