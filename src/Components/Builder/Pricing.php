<?php

namespace Jiannius\Atom\Components\Builder;

use Illuminate\View\Component;

class Pricing extends Component
{
    public $plan;
    public $prices;

    /**
     * Contructor
     * 
     * @return void
     */
    public function __construct(
        $plan = null,
        $prices = []
    ) {
        $this->plan = $plan;
        $this->prices = $prices;
    }

    /**
     * Render component
     * 
     * @return Response
     */
    public function render()
    {
        return view('atom::components.builder.pricing');
    }
}