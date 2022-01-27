<?php

namespace Jiannius\Atom\Components;

use Illuminate\View\Component;

class Dropdown extends Component
{
    public $right;
    public $href;
    public $route;
    public $params;

    /**
     * Contructor
     * 
     * @return void
     */
    public function __construct(
        $right = false,
        $href = null,
        $route = null,
        $params = null
    ) {
        $this->right = $right;
        $this->href = $href;
        $this->route = $route;
        $this->params = $params;
    }

    /**
     * Render component
     * 
     * @return Response
     */
    public function render()
    {
        return view('atom::components.dropdown');
    }
}