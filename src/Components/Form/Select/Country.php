<?php

namespace Jiannius\Atom\Components\Form\Select;

use Illuminate\View\Component;

class Country extends Component
{
    /**
     * Render
     */
    public function render()
    {
        return view('atom::components.form.select.country');
    }
}