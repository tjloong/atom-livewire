<?php

namespace Jiannius\Atom\Components\Form;

use Illuminate\View\Component;

class Select extends Component
{
    public $options;
    public $multiple;
    public $selected;

    /**
     * Construct
     */
    public function __construct(
        $options = null,
        $selected = null,
        $multiple = false
    ) {
        $this->options = $options;
        $this->selected = $selected;
        $this->multiple = $multiple;
    }

    /**
     * Render
     */
    public function render()
    {
        return view('atom::components.form.select');
    }
}