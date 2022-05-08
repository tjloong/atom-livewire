<?php

namespace Jiannius\Atom\Components;

use Illuminate\View\Component;

class Drawer extends Component
{
    public $uid;

    /**
     * Contructor
     */
    public function __construct($uid = null)
    {
        $this->uid = $uid ?? 'drawer-'.uniqid();
    }

    /**
     * Render
     */
    public function render()
    {
        return view('atom::components.drawer');
    }
}