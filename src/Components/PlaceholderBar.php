<?php

namespace Jiannius\Atom\Components;

use Illuminate\View\Component;

class PlaceholderBar extends Component
{
    public $width;
    public $height;

    /**
     * Constructor
     */
    public function __construct($size = null)
    {
        $split = str($size)->split('/x/')->filter();
        $width = $split->first() ?? '100%';
        $height = $split->count() > 1 ? $split->last() : null;

        if (str($width)->is('*%')) $this->width = $width;
        elseif ($width) $this->width = $width.'px';

        if (str($height)->is('*%')) $this->height = $height;
        elseif ($height) $this->height = $height.'px';
    }

    /**
     * Render
     */
    public function render()
    {
        return view('atom::components.placeholder-bar');
    }
}