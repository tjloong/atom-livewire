<?php

namespace Jiannius\Atom\Components\Input;

use Illuminate\View\Component;

class Picker extends Component
{
    public $getter;
    public $labelKey;

    /**
     * Contructor
     * 
     * @return void
     */
    public function __construct($getter, $labelKey = 'name')
    {
        $this->getter = $getter;
        $this->labelKey = $labelKey;
    }

    /**
     * Render component
     * 
     * @return Response
     */
    public function render()
    {
        return view('atom::components.input.picker');
    }
}