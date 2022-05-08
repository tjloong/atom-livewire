<?php

namespace Jiannius\Atom\Components\Form;

use Illuminate\View\Component;

class Select extends Component
{
    public $options;

    /**
     * Contructor
     */
    public function __construct($options = [])
    {
        $this->options = collect($options)->map(fn($opt) => (object)$opt)->values();
    }

    /**
     * Render
     */
    public function render()
    {
        return view('atom::components.form.select');
    }
}