<?php

namespace Jiannius\Atom\Components\Analytics;

use Illuminate\View\Component;

class Fbpixel extends Component
{
    /**
     * Render
     */
    public function render()
    {
        return view('atom::components.analytics.fbpixel');
    }
}