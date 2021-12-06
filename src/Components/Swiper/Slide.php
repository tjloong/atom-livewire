<?php

namespace Jiannius\Atom\Components\Swiper;

use Illuminate\View\Component;

class Slide extends Component
{
    /**
     * Contructor
     * 
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Render component
     * 
     * @return Response
     */
    public function render()
    {
        return view('atom::components.swiper.slide');
    }
}