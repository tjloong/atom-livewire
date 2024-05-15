<?php

namespace Jiannius\Atom\Components\Box\Loading;

use Illuminate\View\Component;

class Index extends Component
{
    public function render()
    {
        return view('atom::components.box.loading.index');
    }
}