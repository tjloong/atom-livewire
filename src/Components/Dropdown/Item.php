<?php

namespace Jiannius\Atom\Components\Dropdown;

use Illuminate\View\Component;

class Item extends Component
{
    public $href;
    public $route;
    public $params;

    /**
     * Contructor
     */
    public function __construct(
        $href = null,
        $route = null,
        $params = null
    ) {
        $this->href = $href;
        $this->route = $route;
        $this->params = $params;
    }

    /**
     * Render
     */
    public function render()
    {
        return view('atom::components.dropdown.item');
    }
}