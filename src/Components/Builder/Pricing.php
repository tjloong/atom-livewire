<?php

namespace Jiannius\Atom\Components\Builder;

use Illuminate\View\Component;

class Pricing extends Component
{
    public $cta;
    public $plan;
    public $prices;
    public $trial;

    /**
     * Contructor
     * 
     * @return void
     */
    public function __construct(
        $plan = null,
        $prices = [],
        $trial = null,
        $cta = null
    ) {
        $this->plan = $plan;
        $this->prices = $prices;
        $this->trial = $trial;
        $this->cta = [
            'text' => $cta['text'] ?? 'Get Started',
            'href' => $cta['href'] ?? null,
            'color' => $cta['color'] ?? 'theme',
            'icon' => $cta['icon'] ?? null,
            'icon_type' => $cta['icon_type'] ?? null,
            'disabled' => $cta['disabled'] ?? false,
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