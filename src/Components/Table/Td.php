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
        else if (is_numeric($amount)) $this->label = currency($amount, $currency);
        else if (!empty($percentage)) $this->label = number_format($percentage, 2).'%';
        else if (is_numeric($count)) {
            $this->label = $count 
                ? implode(' ', array_filter([$count, $uom ? str($uom)->plural() : null]))
                : null;
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