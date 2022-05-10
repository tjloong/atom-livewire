<?php

namespace Jiannius\Atom\Components\Form;

use Illuminate\View\Component;

class DateRange extends Component
{
    public $uid;
    
    /**
     * Constructor
     */
    public function __construct($uid = null)
    {
        $this->uid = $uid ?? 'date-range-'.uniqid();
    }

    /**
     * Render
     */
    public function render()
    {
        return view('atom::components.form.date-range');
    }
}