<?php

namespace Jiannius\Atom\Components\Dropdown;

use Illuminate\View\Component;

class Index extends Component
{
    public $right;

    /**
     * Contructor
     * 
     * @return void
     */
    public function __construct($right = false)
    {
        $this->right = $right;
    }

    /**
     * Render component
     * 
     * @return Response
     */
    public function render()
    {
        return view('atom::components.dropdown.index');
    }
}