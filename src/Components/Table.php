<?php

namespace Jiannius\Atom\Components;

use Illuminate\View\Component;

class Table extends Component
{
    public $uid;
    public $showSearch;
    public $showExport;
    public $btnColor;

    protected $btnColors = [
        'red' => 'text-red-500',
        'green' => 'text-green-500',
        'yellow' => 'text-yellow-500',
        'blue' => 'text-red-500',
        'gray' => 'text-gray-900'
    ];

    /**
     * Create the component instance.
     *
     * @return void
     */
    public function __construct(
        $uid = 'table',
        $search = true, 
        $export = false, 
        $btnColor = 'gray'
    ) {
        $this->uid = $uid;
        $this->showSearch = $search;
        $this->showExport = $export;
        $this->btnColor = $this->btnColors[$btnColor];
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\View\View|\Closure|string
     */
    public function render()
    {
        return view('atom::components.table');
    }
}