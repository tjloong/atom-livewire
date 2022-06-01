<?php

namespace Jiannius\Atom\Components\Form;

use Illuminate\View\Component;

class Image extends Component
{
    public $uid;
    public $dimension;

    /**
     * Contructor
     */
    public function __construct(
        $uid = null,
        $dimension = '100x100'
    ) {
        $this->uid = $uid ?? 'image-input';
        $this->dimension = $this->getDimension($dimension);
    }

    /**
     * Get dimension
     */
    public function getDimension($default)
    {
        $split = explode('x', $default);
        $width = $split[0];
        $height = $split[1];

        return (object)compact('width', 'height');
    }

    /**
     * Render
     */
    public function render()
    {
        return view('atom::components.form.image');
    }
}