<?php

namespace Jiannius\Atom\Components;

use Illuminate\View\Component;

class Card extends Component
{
    public $href;
    public $image;

    /**
     * Contructor
     * 
     * @return void
     */
    public function __construct(
        $image = null,
        $href = null
    ) {
        $this->href = $href;
        $this->image = [
            'url' => is_string($image) ? $image : ($image['url'] ?? null),
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
        return view('atom::components.card');
    }
}