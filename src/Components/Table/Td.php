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
        $limit = null,
        $tooltip = null
    ) {
        $this->tooltip = $tooltip;

        if ($label) $this->label = $label;
        else if (!empty($amount)) $this->label = currency($amount, $currency);
        else if (!empty($percentage)) $this->label = number_format($percentage, 2).'%';

        if ($limit) {
            if (!$this->tooltip && strlen($this->label) > $limit) $this->tooltip = $this->label;
            $this->label = str()->limit($this->label, $limit);
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