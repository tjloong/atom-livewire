<?php

namespace Jiannius\Atom\Components;

use Illuminate\View\Component;

class Avatar extends Component
{
    public $size;
    public $colors;

    /**
     * Constructor
     */
    public function __construct($size = '50')
    {
        $this->size = $size;
        $this->colors = [
            '#feca57',
            '#ee5253',
            '#5f27cd',
            '#2e86de',
            '#01a3a4',
            '#0abde3',
            '#222f3e',
            '#f368e0',
        ];
    }

    /**
     * Render
     */
    public function render()
    {
        return view('atom::components.avatar');
    }
}