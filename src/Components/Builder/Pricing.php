<?php

namespace Jiannius\Atom\Components\Builder;

use Illuminate\View\Component;

class Pricing extends Component
{
    public $cta;
    public $plan;
    public $prices;

    /**
     * Contructor
     * 
     * @return void
     */
    public function __construct(
        $plan = null,
        $prices = [],
        $cta = null
    ) {
        $this->plan = $plan;
        $this->prices = $prices;

        $this->cta = [
            'text' => $cta['text'] ?? 'Get Started',
            'href' => $cta['href'] ?? null,
        ];
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