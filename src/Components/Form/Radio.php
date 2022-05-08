<?php

namespace Jiannius\Atom\Components\Form;

use Illuminate\View\Component;

class Radio extends Component
{
    /**
     * Render
     */
    public function render()
    {
        return view('atom::components.form.radio');
    }
}