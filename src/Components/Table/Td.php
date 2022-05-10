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
        $date = null,
        $datetime = null,
        $percentage = null
    ) {
        if ($label) $this->label = $label;
        else if ($percentage) $this->label = number_format($percentage, 2).'%';
        else if ($date) $this->label = format_date($date);
        else if ($datetime) $this->label = format_date($datetime, 'datetime');
    }

    /**
     * Render
     */
    public function render()
    {
        return view('atom::components.table.td');
    }
}