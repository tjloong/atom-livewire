<?php

namespace Jiannius\Atom\Components\Swiper;

use Illuminate\View\Component;

class Index extends Component
{
    public $config;

    /**
     * Contructor
     * 
     * @return void
     */
    public function __construct($config = [])
    {
        $this->config = $config;
    }

    /**
     * Render component
     * 
     * @return Response
     */
    public function render()
    {
        return view('atom::components.swiper.index');
    }
}