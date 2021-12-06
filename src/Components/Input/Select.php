<?php

namespace Jiannius\Atom\Components\Input;

use Illuminate\View\Component;

class Select extends Component
{
    public $options;

    /**
     * Contructor
     * 
     * @return void
     */
    public function __construct($options = [])
    {
        $this->options = collect($options)->map(fn($opt) => (object)$opt)->values();
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\View\View|\Closure|string
     */
    public function render()
    {
        return view('atom::components.input.select');
    }
}