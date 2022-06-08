<?php

namespace Jiannius\Atom\Components\Modal;

use Illuminate\View\Component;

class Index extends Component
{
    public $uid;

    /**
     * Contructor
     */
    public function __construct($uid = null)
    {
        $this->uid = $uid ?: 'modal';
    }

    /**
     * Render
     */
    public function render()
    {
        return view('atom::components.modal.index');
    }
}