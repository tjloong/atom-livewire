<?php

namespace Jiannius\Atom\Components;

use Illuminate\View\Component;

class FbPixel extends Component
{
    public $id;
    public $noscript;

    /**
     * Create the component instance.
     *
     * @param boolean $noscript
     * @return void
     */
    public function __construct($noscript = false)
    {
        $this->noscript = $noscript;
        $this->id = config('atom.fbpixel_id') ?? site_settings('fbpixel_id');
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\View\View|\Closure|string
     */
    public function render()
    {
        return view('atom::components.fb-pixel');
    }
}