<?php

namespace Jiannius\Atom\Components\Input;

use Illuminate\View\Component;

class Title extends Component
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
        return view('atom::components.input.title');
    }
}