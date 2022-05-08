<?php

namespace Jiannius\Atom\Components\Analytics;

use Illuminate\View\Component;

class Gtm extends Component
{
    public $id;
    public $noscript;

    /**
     * Constructor
     */
    public function __construct($noscript = false)
    {
        $this->noscript = $noscript;
        $this->id = config('atom.gtm_id') ?? site_settings('gtm_id');
    }

    /**
     * Render
     */
    public function render()
    {
        return view('atom::components.analytics.gtm');
    }
}