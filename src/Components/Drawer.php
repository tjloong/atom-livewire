<?php

namespace Jiannius\Atom\Components;

use Illuminate\View\Component;

class Drawer extends Component
{
    public $uid;

    /**
     * Contructor
     * 
     * @return void
     */
    public function __construct($uid = 'drawer')
    {
        $this->uid = $uid;
    }

    /**
     * Render component
     * 
     * @return Response
     */
    public function render()
    {
        return view('atom::components.drawer');
    }
}