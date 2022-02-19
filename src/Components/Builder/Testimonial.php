<?php

namespace Jiannius\Atom\Components\Builder;

use Illuminate\View\Component;

class Testimonial extends Component
{
    public $dark;
    public $align;
    public $image;
    public $customer;

    /**
     * Contructor
     * 
     * @return void
     */
    public function __construct(
        $dark = false,
        $align = null,
        $image = null,
        $customer = null
    ) {
        $this->dark = $dark;
        $this->align = $align;

        $this->image = [
            'url' => is_string($image) ? $image : ($image['url'] ?? null),
            'position' => $image['position'] ?? 'top',
            'alt' => $image['alt'] ?? 'testimonial-avatar',
            'circle' => $image['circle'] ?? false,
        ];

        $this->customer = [
            'name' => is_string($customer) ? $customer : ($customer['name'] ?? null),
            'company' => $customer['company'] ?? null,
            'designation' => $customer['designation'] ?? null,
            'align' => $customer['align'] ?? null,
        ];
    }

    /**
     * Render component
     * 
     * @return Response
     */
    public function render()
    {
        return view('atom::components.builder.testimonial');
    }
}