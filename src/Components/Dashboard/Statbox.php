<?php

namespace Jiannius\Atom\Components\Dashboard;

use Illuminate\View\Component;

class Statbox extends Component
{
    /**
     * Render
     */
    public function render()
    {
        return view('atom::components.dashboard.statbox');
    }
}