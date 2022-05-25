<?php

namespace Jiannius\Atom\Components\Slider;

use Illuminate\View\Component;

class Nav extends Component
{
    /**
     * Contructor
     */
    public function __construct() 
    {
        //
    }

    /**
     * Render
     */
    public function render()
    {
        return view('atom::components.slider.nav');
    }
}