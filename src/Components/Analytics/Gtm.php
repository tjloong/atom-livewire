<?php

namespace Jiannius\Atom\Components\Analytics;

use Illuminate\View\Component;

class Gtm extends Component
{
    /**
     * Render
     */
    public function render()
    {
        return view('atom::components.analytics.gtm');
    }
}