<?php

namespace Jiannius\Atom\Components;

use Illuminate\View\Component;

class Loader extends Component
{
    public $size;
    public $color;
    public $fullscreen;

    /**
     * Contructor
     * 
     * @return void
     */
    public function __construct($size = '24px', $color = 'currentcolor', $fullscreen = false)
    {
        $this->size = $size;
        $this->color = $color;
        $this->fullscreen = $fullscreen;
    }

    /**
     * Render component
     * 
     * @return Response
     */
    public function render()
    {
        return view('atom::components.loader');
    }
}