<?php

namespace Jiannius\Atom\Components\Input;

use Illuminate\View\Component;

class Field extends Component
{
    /**
     * Render component
     * 
     * @return Response
     */
    public function render()
    {
        return view('atom::components.input.field');
    }
}