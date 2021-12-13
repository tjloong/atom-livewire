<?php

namespace Jiannius\Atom\Components\Builder;

use Illuminate\View\Component;

class Hero extends Component
{
    public $align;
    public $text;
    public $image;
    public $imagePosition;
    
    /**
     * Contructor
     * 
     * @return void
     */
    public function __construct(
        $align = 'left', 
        $text = null,
        $image = null, 
        $imagePosition = 'bg'
    ) {
        $this->align = $align;
        $this->text = $text;
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