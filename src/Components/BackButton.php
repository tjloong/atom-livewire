<?php

namespace Jiannius\Atom\Components;

use Illuminate\View\Component;

class BackButton extends Component
{
    /**
     * Render component
     * 
     * @return Response
     */
    public function render()
    {
        return view('atom::components.back-button');
    }
}