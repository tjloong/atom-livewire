<?php

namespace Jiannius\Atom\Components\Input;

use Illuminate\View\Component;

class Image extends Component
{
    public $dimension;
    public $placeholder;

    /**
     * Contructor
     * 
     * @return void
     */
    public function __construct($dimension = '100x100', $placeholder = null)
    {
        $this->dimension = $this->getDimension($dimension);
        $this->placeholder = $placeholder;
    }

    /**
     * Render component
     * 
     * @return Response
     */
    public function render()
    {
        return view('atom::components.input.image');
    }

    /**
     * Get dimension
     * 
     * @return array
     */
    public function getDimension($default)
    {
        $split = explode('x', $default);
        $width = $split[0];
        $height = $split[1];

        return (object)compact('width', 'height');
    }
}