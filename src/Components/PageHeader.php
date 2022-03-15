<?php

namespace Jiannius\Atom\Components;

use Illuminate\View\Component;

class PageHeader extends Component
{
    public $back;

    /**
     * Contructor
     * 
     * @return void
     */
    public function __construct($back = false)
    {
        if (is_string($back) || is_bool($back)) $this->back = $back;
        else $this->back = (optional(breadcrumbs()->previous())['url'] ?? true);
    }

    /**
     * Render component
     * 
     * @return Response
     */
    public function render()
    {
        return view('atom::components.page-header');
    }
}