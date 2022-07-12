<?php

namespace Jiannius\Atom\Components\PageHeader;

use Illuminate\View\Component;

class Back extends Component
{
    /**
     * Render component
     * 
     * @return Response
     */
    public function render()
    {
        return view('atom::components.page-header.back');
    }
}