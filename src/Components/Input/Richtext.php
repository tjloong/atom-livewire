<?php

namespace Jiannius\Atom\Components\Input;

use Illuminate\View\Component;

class Richtext extends Component
{
    public $toolbar;

    /**
     * Contructor
     * 
     * @return void
     */
    public function __construct($toolbar = null)
    {
        $this->toolbar = $toolbar
            ? (is_array($toolbar) ? $toolbar : explode(',', $toolbar))
            : null;
    }

    /**
     * Render component
     * 
     * @return Response
     */
    public function render()
    {
        return view('atom::components.input.richtext');
    }
}