<?php

namespace Jiannius\Atom\Components;

use Illuminate\View\Component;

class FbPixel extends Component
{
    public $id;
    public $noscript;

    /**
     * Constructor
     */
    public function __construct($noscript = false)
    {
        $this->noscript = $noscript;
        $this->id = config('atom.fbpixel_id') ?? site_settings('fbpixel_id');
    }

    /**
     * Render
     */
    public function render()
    {
        return view('atom::components.fb-pixel');
    }
}