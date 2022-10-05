<?php

namespace Jiannius\Atom\Components;

use Illuminate\View\Component;

class Drawer extends Component
{
    public $uid;

    /**
     * Contructor
     */
    public function __construct($uid = 'drawer')
    {
        $this->uid = $uid;
    }

    /**
     * Render
     */
    public function render()
    {
        return view('atom::components.drawer');
    }
}