<?php

namespace Jiannius\Atom\Components\PageHeader;

use Illuminate\View\Component;

class Index extends Component
{
    public $back;

    /**
     * Contructor
     */
    public function __construct($back = false)
    {
        if ($back === 'auto') {
            if ($prev = data_get(breadcrumbs()->previous(), 'url')) $this->back = $prev;
            else $this->back = false;
        }
        else if (is_string($back)) $this->back = $back;
        else if ($back === true) $this->back = data_get(breadcrumbs()->previous(), 'url', true);
    }

    /**
     * Render
     */
    public function render()
    {
        return view('atom::components.page-header.index');
    }
}