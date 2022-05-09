<?php

namespace Jiannius\Atom\Components\Dropdown;

use Illuminate\View\Component;

class Index extends Component
{
    public $right;

    /**
     * Contructor
     */
    public function __construct($right = false) 
    {
        $this->right = $right;
    }

    /**
     * Render
     */
    public function render()
    {
        return view('atom::components.dropdown.index');
    }
}