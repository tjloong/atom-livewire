<?php

namespace Jiannius\Atom\Components;

use Illuminate\View\Component;

class FullscreenLoader extends Component
{
    /**
     * Create the component instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\View\View|\Closure|string
     */
    public function render()
    {
        return view('atom::components.fullscreen-loader');
    }
}