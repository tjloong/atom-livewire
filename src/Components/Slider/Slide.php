<?php

namespace Jiannius\Atom\Components\Slider;

use Illuminate\View\Component;

class Slide extends Component
{
    public $image;

    /**
     * Contructor
     */
    public function __construct($image = null) 
    {
        $width = data_get($image, 'width');
        $height = data_get($image, 'height');

        $this->image = [
            'url' => is_string($image) 
                ? $image 
                : data_get($image, 'url'),
            'fit' => data_get($image, 'fit', 'cover'),
            'width' => $width ?? '100%',
            'height' => $height ?? '100%',
        ];
    }

    /**
     * Render component
     * 
     * @return Response
     */
    public function render()
    {
        return view('atom::components.slider.slide');
    }
}