<?php

namespace Jiannius\Atom\Components\Input;

use Illuminate\View\Component;

class Sortable extends Component
{
    public $el;
    public $config;
    public $value;

    /**
     * Contructor
     * 
     * @return void
     */
    public function __construct(
        $el = 'div',
        $config = [],
        $value = null,
    ) {
        $this->el = $el;
        $this->config = $config;
        $this->value = $value;
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