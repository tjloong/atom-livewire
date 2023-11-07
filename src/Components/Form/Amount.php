<?php

namespace Jiannius\Atom\Components\Form;

use Illuminate\View\Component;

class Amount extends Component
{
    public function render()
    {
        return view('atom::components.form.amount');
    }
}