<?php

namespace Jiannius\Atom\Components\Input;

use Illuminate\View\Component;

class Address extends Component
{
    /**
     * Constructor
     * 
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\View\View|\Closure|string
     */
    public function render()
    {
        return view('atom::components.input.address');
    }
}