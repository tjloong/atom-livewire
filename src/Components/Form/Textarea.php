<?php

namespace Jiannius\Atom\Components\Form;

use Illuminate\View\Component;

class Textarea extends Component
{
    /**
     * Render
     */
    public function render()
    {
        return view('atom::components.form.textarea');
    }
}