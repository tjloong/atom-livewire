<?php

namespace Jiannius\Atom\Components;

use Illuminate\View\Component;

class Tabs extends Component
{
    public $active;

    /**
     * Create the component instance.
     *
     * @return void
     */
    public function __construct($active = false)
    {
        $this->active = $active;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\View\View|\Closure|string
     */
    public function render()
    {
        return view('atom::components.tabs');
    }
}