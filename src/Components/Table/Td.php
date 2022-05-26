<?php

namespace Jiannius\Atom\Components\Table;

use Illuminate\View\Component;

class Td extends Component
{
    public $label;

    /**
     * Constructor
     */
    public function __construct(
        $label = null,
        $percentage = null,
        $amount = null,
        $currency = null
    ) {
        if ($label) $this->label = $label;
        else if (!empty($amount)) $this->label = currency($amount, $currency);
        else if (!empty($percentage)) $this->label = number_format($percentage, 2).'%';
    }

    /**
     * Render
     */
    public function render()
    {
        return view('atom::components.table.td');
    }
}