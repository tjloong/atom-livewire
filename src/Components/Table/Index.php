<?php

namespace Jiannius\Atom\Components\Table;

use Illuminate\View\Component;

class Index extends Component
{
    public $uid;
    
    /**
     * Constructor
     */
    public function __construct($uid = null)
    {
        $this->uid = $uid ?? 'table';
    }

    /**
     * Render
     */
    public function render()
    {
        return view('atom::components.table.index');
    }
}