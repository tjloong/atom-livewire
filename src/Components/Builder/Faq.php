<?php

namespace Jiannius\Atom\Components\Builder;

use Illuminate\View\Component;

class Faq extends Component
{
    public $cols;
    public $sets;
    public $align;

    /**
     * Contructor
     * 
     * @return void
     */
    public function __construct(
        $sets = [],
        $cols = '1',
        $align = null
    ) {
        $this->cols = $cols;
        $this->sets = $this->getSets($sets);
        $this->align = $align;
    }

    /**
     * Render component
     * 
     * @return Response
     */
    public function render()
    {
        return view('atom::components.builder.faq');
    }

    /**
     * Get faq sets
     * 
     * @return Collection
     */
    private function getSets($sets)
    {
        return collect($sets)->map(function($set) {
            $set['excerpt'] = html_excerpt($set['answer']);
            return (object)$set;
        });
    }
}