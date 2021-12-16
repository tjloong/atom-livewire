<?php

namespace Jiannius\Atom\Components\Builder;

use Illuminate\View\Component;

class NavbarItem extends Component
{
    /**
     * Contructor
     * 
     * @return void
     */
    public function __construct() {

    }

    /**
     * Render component
     * 
     * @return Response
     */
    public function render()
    {
        return view('atom::components.builder.navbar-item');
    }
}