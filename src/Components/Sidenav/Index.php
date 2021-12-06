<?php

namespace Jiannius\Atom\Components\Sidenav;

use Illuminate\View\Component;

class Index extends Component
{
    /**
     * Contructor
     * 
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Render component
     * 
     * @return Response
     */
    public function render()
    {
        return view('atom::components.sidenav.index');
    }
}