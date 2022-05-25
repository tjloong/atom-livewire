<?php

namespace Jiannius\Atom\Components\Slider;

use Illuminate\View\Component;

class Index extends Component
{
    public $image;
    public $align;
    public $valign;
    public $thumbs;
    public $overlay;

    /**
     * Contructor
     * 
     * @return void
     */
    public function __construct(
        $image = null,
        $align = 'center',
        $valign = 'center',
        $overlay = false,
        $thumbs = null
    ) {
        $this->align = $align;
        $this->valign = $valign;
        $this->overlay = $overlay;

        $this->image = [
            'url' => is_string($image) ? $image : ($image['url'] ?? null),
            'fit' => $image['fit'] ?? 'cover',
            'width' => $image['width'] ?? null,
            'height' => $image['height'] ?? null,
        ];

        $this->thumbs = [
            'position' => is_string($thumbs) 
                ? $thumbs 
                : ($thumbs ? ($thumbs['position'] ?? 'bottom') : null),
            'config' => $thumbs['config'] ?? null,
            'height' => $thumbs['height'] ?? null,
        ];
    }

    /**
     * Render component
     * 
     * @return Response
     */
    public function render()
    {
        return view('atom::components.slider.index');
    }
}