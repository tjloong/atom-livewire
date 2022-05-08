<?php

namespace Jiannius\Atom\Components\Form;

use Illuminate\View\Component;

class Currency extends Component
{
    public $countries;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->countries = metadata()->countries()
            ->filter(fn($cn) => !empty($cn->currency) && !empty($cn->currency->code));
    }

    /**
     * Render
     */
    public function render()
    {
        return view('atom::components.form.currency');
    }
}