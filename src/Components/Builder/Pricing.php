<?php

namespace Jiannius\Atom\Components\Builder;

use Illuminate\View\Component;

class Pricing extends Component
{
    public $cta;
    public $plan;
    public $prices;
    public $recurrings;

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
        $this->recurrings = collect($prices)->map(function($price) {
            $label = $price['expired_after'];
            [$n, $unit] = explode(' ', $price['expired_after']);

            if ($n <= 1) {
                if ($unit === 'day') $label = 'daily';
                if ($unit === 'month') $label = 'monthly';
                if ($unit === 'year') $label = 'yearly';
            }

            return ['value' => $price['expired_after'], 'label' => $label];
        })->values()->all();

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