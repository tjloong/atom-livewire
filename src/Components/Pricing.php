<?php

namespace Jiannius\Atom\Components;

use Illuminate\View\Component;

class Pricing extends Component
{
    public $plan;
    public $prices;
    public $trial;
    public $variants;

    /**
     * Contructor
     * 
     * @return void
     */
    public function __construct(
        $plan = null,
        $prices = [],
        $trial = null,
    ) {
        $this->plan = $plan;
        $this->prices = $prices;
        $this->trial = $trial;
        $this->variants = collect($this->prices)->pluck('recurring')->filter()->toArray();
    }

    /**
     * Render component
     * 
     * @return Response
     */
    public function render()
    {
        return view('atom::components.pricing');
    }
}