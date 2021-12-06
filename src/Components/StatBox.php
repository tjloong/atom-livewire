<?php

namespace Jiannius\Atom\Components;

use Illuminate\View\Component;

class StatBox extends Component
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
        return view('atom::components.stat-box');
    }
}