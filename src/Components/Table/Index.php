<?php

namespace Jiannius\Atom\Components\Table;

use Illuminate\View\Component;

class Index extends Component
{
    public $showExport;
    public $showFilters;

    /**
     * Create the component instance.
     *
     * @return void
     */
    public function __construct($export = false, $filters = false)
    {
        $this->showExport = $export;
        $this->showFilters = $filters;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\View\View|\Closure|string
     */
    public function render()
    {
        return view('atom::components.table.index');
    }
}