<?php

namespace Jiannius\Atom\Components;

use Illuminate\View\Component;

class PlanAlert extends Component
{
    /**
     * Render
     */
    public function render()
    {
        return view('atom::components.plan-alert');
    }
}