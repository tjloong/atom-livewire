<?php

namespace Jiannius\Atom\Components\Builder;

use Illuminate\Support\Str;
use Illuminate\View\Component;

class Faq extends Component
{
    public $cols;
    public $align;
    public $items;

    /**
     * Contructor
     * 
     * @return void
     */
    public function __construct(
        $items = [],
        $cols = '1',
        $align = null
    ) {
        $this->cols = $cols;
        $this->align = $align;
        $this->items = $this->getItems($items);
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
     * Get items
     * 
     * @return Collection
     */
    private function getItems($items)
    {
        return collect($items)->map(function($item) {
            $item['excerpt'] = html_excerpt($item['answer']);
            return (object)$item;
        });
    }
}