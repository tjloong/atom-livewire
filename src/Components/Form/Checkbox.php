<?php

namespace Jiannius\Atom\Components\Form;

use Illuminate\View\Component;

class Checkbox extends Component
{
    /**
     * Render
     */
    public function render()
    {
        return view('atom::components.form.checkbox');
    }
}