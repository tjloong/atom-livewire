<?php

namespace Jiannius\Atom\Components\Table;

use Illuminate\View\Component;

class Td extends Component
{
    public $label;
    public $tooltip;

    /**
     * Constructor
     */
    public function __construct(
        $label = null,
        $percentage = null,
        $amount = null,
        $currency = null,
        $count = null,
        $uom = null,
        $limit = null,
        $tooltip = null
    ) {
        $this->tooltip = $tooltip;

        if (!is_null($label)) $this->label = $label;
        else if (is_numeric($amount) || is_string($amount)) {
            $this->label = is_numeric($amount)? currency($amount, $currency) : $amount;
        }
        else if (is_numeric($percentage) || is_string($percentage)) {
            $this->label = is_numeric($percentage) ? number_format($percentage, 2).'%' : $percentage;
        }
        else if (is_numeric($count) || is_string($count)) {
            $this->label = is_numeric($count)
                ? $count.($uom ? (' '.str($uom)->plural($count)) : '')
                : $count;
        }

        if ($limit) {
            $this->label = str()->limit($this->label, $limit);
            
            if ($this->tooltip !== false && empty($this->tooltip) && strlen($this->label) > $limit) {
                $this->tooltip = $this->label;
            }
        }
    }

    /**
     * Render
     */
    public function render()
    {
        return view('atom::components.table.td');
    }
}