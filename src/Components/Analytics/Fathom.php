<?php

namespace Jiannius\Atom\Components\Analytics;

use Illuminate\View\Component;

class Fathom extends Component
{
    /**
     * Render
     */
    public function render(): mixed
    {
        return view('atom::components.analytics.fathom');
    }
}