<?php

namespace Jiannius\Atom\Components;

use Illuminate\View\Component;

class PageHeader extends Component
{
    public $back;

    /**
     * Contructor
     */
    public function __construct($back = false)
    {
        if (is_string($back)) $this->back = $back;
        else if ($back === true) $this->back = (optional(breadcrumbs()->previous())['url'] ?? true);
    }

    /**
     * Render
     */
    public function render()
    {
        return view('atom::components.page-header');
    }
}