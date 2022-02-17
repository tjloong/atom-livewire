<?php

namespace Jiannius\Atom\Components;

use Illuminate\View\Component;

class Breadcrumbs extends Component
{
    public $breadcrumbs;

    /**
     * Contructor
     * 
     * @return void
     */
    public function __construct()
    {
        $this->breadcrumbs = session('breadcrumbs', []);
    }

    /**
     * Render component
     * 
     * @return Response
     */
    public function render()
    {
        return view('atom::components.breadcrumbs');
    }
}