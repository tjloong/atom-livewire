<?php

namespace Jiannius\Atom\Components\Form\Date;

use Illuminate\View\Component;

class Range extends Component
{
    public function render()
    {
        return view('atom::components.form.date.range');
    }
}