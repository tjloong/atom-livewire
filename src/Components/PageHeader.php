<?php

namespace Jiannius\Atom\Components;

use Illuminate\View\Component;

class PageHeader extends Component
{
    public $back;

    /**
     * Contructor
     * 
     * @return void
     */
    public function __construct($back = false)
    {
        $this->back = $back ? $this->getBack() : false;
    }

    /**
     * Render component
     * 
     * @return Response
     */
    public function render()
    {
        return view('atom::components.page-header');
    }

    /**
     * Get back url
     */
    public function getBack()
    {
        $breadcrumbs = session('breadcrumbs', []);

        if (count($breadcrumbs) > 1) return $breadcrumbs[array_key_last($breadcrumbs) - 1]['url'];
        else return true;
    }
}