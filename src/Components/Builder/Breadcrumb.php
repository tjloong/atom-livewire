<?php

namespace Jiannius\Atom\Components\Builder;

use Illuminate\View\Component;

class Breadcrumb extends Component
{
    public $links;

    /**
     * Contructor
     * 
     * @return void
     */
    public function __construct($links = [])
    {
        $this->links = array_filter($links);
    }

    /**
     * Render component
     * 
     * @return Response
     */
    public function render()
    {
        return view('atom::components.builder.breadcrumb');
    }
}