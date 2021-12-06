<?php

namespace Jiannius\Atom\Components;

use Illuminate\View\Component;

class Modal extends Component
{
    public $uid;

    /**
     * Contructor
     * 
     * @return void
     */
    public function __construct($uid = null)
    {
        $this->uid = $uid ? ('modal-' . $uid) : 'modal';
    }

    /**
     * Render component
     * 
     * @return Response
     */
    public function render()
    {
        return view('atom::components.modal');
    }
}