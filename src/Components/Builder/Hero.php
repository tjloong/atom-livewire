<?php

namespace Jiannius\Atom\Components\Builder;

use Illuminate\View\Component;

class Hero extends Component
{
    public $overlay;
    public $align;
    public $valign;
    public $image;
    public $imagePosition;
    public $size;
    public $bgcolor;
    
    /**
     * Contructor
     * 
     * @return void
     */
    public function __construct(
        $align = 'center',
        $valign = 'center',
        $overlay = false,
        $image = null, 
        $imagePosition = 'bg',
        $size = null,
        $bgcolor = null
    ) {
        $this->overlay = $overlay;
        $this->align = $align;
        $this->valign = $valign;
        $this->image = $image;
        $this->imagePosition = $imagePosition;
        $this->size = $size;
        $this->bgcolor = $bgcolor;
    }

    /**
     * Render component
     * 
     * @return Response
     */
    public function render()
    {
        return view('atom::components.builder.hero');
    }
}