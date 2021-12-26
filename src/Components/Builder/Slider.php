<?php

namespace Jiannius\Atom\Components\Builder;

use Illuminate\View\Component;

class Slider extends Component
{
    public $align;
    public $valign;
    public $overlay;
    public $thumbsPosition;

    /**
     * Contructor
     * 
     * @return void
     */
    public function __construct(
        $align = 'center',
        $valign = 'center',
        $overlay = false,
        $thumbsPosition = null
    ) {
        $this->align = $align;
        $this->valign = $valign;
        $this->overlay = $overlay;
        $this->thumbsPosition = $thumbsPosition;
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