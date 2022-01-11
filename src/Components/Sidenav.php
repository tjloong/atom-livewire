<?php

namespace Jiannius\Atom\Components;

use Illuminate\View\Component;

class Sidenav extends Component
{
    public $active;

    /**
     * Contructor
     * 
     * @return void
     */
    public function __construct($active = false)
    {
        $this->active = $active;
    }

    /**
     * Render component
     * 
     * @return Response
     */
    public function render()
    {
        return view('atom::components.sidenav');
    }
}