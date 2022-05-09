<?php

namespace Jiannius\Atom\Components\Form;

use Illuminate\View\Component;

class Sortable extends Component
{
    public $el;
    public $config;
    public $value;

    /**
     * Contructor
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
     * Render
     */
    public function render()
    {
        return view('atom::components.form.sortable');
    }
}