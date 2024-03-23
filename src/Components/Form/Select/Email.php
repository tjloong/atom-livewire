<?php

namespace Jiannius\Atom\Components\Form\Select;

use Illuminate\View\Component;

class Email extends Component
{
    public function render()
    {
        return view('atom::components.form.select.email');
    }
}