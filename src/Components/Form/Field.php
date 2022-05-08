<?php

namespace Jiannius\Atom\Components\Form;

use Illuminate\View\Component;

class Field extends Component
{
    /**
     * Render component
     */
    public function render()
    {
        return view('atom::components.form.field');
    }
}