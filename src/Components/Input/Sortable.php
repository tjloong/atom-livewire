<?php

namespace Jiannius\Atom\Components\Input;

use Illuminate\View\Component;

class Sortable extends Component
{
    public $el;
    public $config;

    /**
     * Contructor
     * 
     * @return void
     */
    public function __construct(
        $el = 'div',
        $config = []
    ) {
        $this->el = $el;
        $this->config = $config;
    }

    /**
     * Render component
     * 
     * @return Response
     */
    public function render()
    {
        return view('atom::components.input.sortable');
    }
}