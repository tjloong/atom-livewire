<?php

namespace Jiannius\Atom\Components\Form;

use Illuminate\View\Component;

class Time extends Component
{
    /**
     * Render
     */
    public function render()
    {
        return view('atom::components.form.time');
    }
}