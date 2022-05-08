<?php

namespace Jiannius\Atom\Components\Form;

use Illuminate\View\Component;

class Picker extends Component
{
    public $getter;
    public $labelKey;

    /**
     * Contructor
     */
    public function __construct($getter, $labelKey = 'name')
    {
        $this->getter = $getter;
        $this->labelKey = $labelKey;
    }

    /**
     * Render
     */
    public function render()
    {
        return view('atom::components.form.picker');
    }
}