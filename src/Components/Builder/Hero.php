<?php

namespace Jiannius\Atom\Components\Builder;

use Illuminate\View\Component;

class Hero extends Component
{
    public $overlay;
    public $align;
    public $valign;
    public $image;
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
        $size = null,
        $bgcolor = null
    ) {
        $this->overlay = $overlay;
        $this->align = $align;
        $this->valign = $valign;
        $this->size = $size;
        $this->bgcolor = $bgcolor;

        $this->image = [
            'url' => is_string($image) ? $image : ($image['url'] ?? null),
            'position' => $image['position'] ?? 'bg',
            'alt' => $image['alt'] ?? null,
        ];
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