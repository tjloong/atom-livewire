<?php

namespace Jiannius\Atom\Components\Analytics;

use Illuminate\View\Component;

class Ga extends Component
{
    public $id;
    public $noscript;

    /**
     * Constructor
     */
    public function __construct($noscript = false)
    {
        $this->noscript = $noscript;
        $this->id = config('atom.ga_id') ?? settings('ga_id');
    }

    /**
     * Render
     */
    public function render()
    {
        return view('atom::components.analytics.ga');
    }
}