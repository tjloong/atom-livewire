<?php

namespace Jiannius\Atom\Components\Notify;

use Illuminate\View\Component;

class Alert extends Component
{
    /**
     * Contructor
     * 
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Render component
     * 
     * @return Response
     */
    public function render()
    {
        return view('atom::components.notify.alert');
    }
}