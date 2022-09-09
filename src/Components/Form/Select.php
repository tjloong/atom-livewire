<?php

namespace Jiannius\Atom\Components\Form;

use Illuminate\View\Component;

class Select extends Component
{
    public $uid;
    public $options;
    public $multiple;
    public $selected;

    /**
     * Construct
     */
    public function __construct(
        $uid = null,
        $options = null,
        $selected = null,
        $multiple = false
    ) {
        $this->uid = $uid ?? str()->uuid();
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