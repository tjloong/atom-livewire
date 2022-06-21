<?php

namespace Jiannius\Atom\Components;

use Illuminate\View\Component;

class Lightbox extends Component
{
    public $uid;

    /**
     * Constructor
     */
    public function __construct($uid = null)
    {
        $this->uid = $uid ?? 'lightbox';
    }

    /**
     * Render
     */
    public function render()
    {
        return view('atom::components.lightbox');
    }
}