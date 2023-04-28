<?php

namespace Jiannius\Atom\Components\Analytics;

use Illuminate\View\Component;

class Ga extends Component
{
    /**
     * Render
     */
    public function render()
    {
        return view('atom::components.analytics.ga');
    }
}