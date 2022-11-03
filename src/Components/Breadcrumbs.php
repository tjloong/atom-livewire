<?php

namespace Jiannius\Atom\Components;

use Illuminate\View\Component;

class Breadcrumbs extends Component
{
    public $trails = [];

    /**
     * Contructor
     * 
     * @return void
     */
    public function __construct()
    {
        $home = breadcrumbs()->get('home');
        $trails = breadcrumbs()->get('trails');
        $fallback = breadcrumbs()->get('fallback');

        if ($home && $trails) $this->trails = array_merge([$home], array_filter($trails));
        else if ($fallback) $this->trails = array_filter($fallback);
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