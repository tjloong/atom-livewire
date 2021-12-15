<?php

namespace Jiannius\Atom\Components\Builder;

use Illuminate\View\Component;

class Hero extends Component
{
    public $dark;
    public $align;
    public $image;
    public $imagePosition;
    
    /**
     * Contructor
     * 
     * @return void
     */
    public function __construct(
        $dark = false,
        $align = 'left', 
        $image = null, 
        $imagePosition = 'bg'
    ) {
        $this->dark = $dark;
        $this->align = $align;
        $this->image = $image;
        $this->imagePosition = $imagePosition;
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