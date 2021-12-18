<?php

namespace Jiannius\Atom\Components\Builder;

use Illuminate\View\Component;

class Slider extends Component
{
    public $align;
    public $valign;
    public $overlay;

    /**
     * Contructor
     * 
     * @return void
     */
    public function __construct(
        $align = 'center',
        $valign = 'center',
        $overlay = false
    ) {
        $this->align = $align;
        $this->valign = $valign;
        $this->overlay = $overlay;
    }

    /**
     * Render component
     * 
     * @return Response
     */
    public function render()
    {
        return view('atom::components.builder.slider');
    }
}