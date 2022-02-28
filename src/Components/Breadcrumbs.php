<?php

namespace Jiannius\Atom\Components;

use Illuminate\View\Component;

class Breadcrumbs extends Component
{
    public $home;
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

        if ($home && $trails) {
            $this->home = $home;
            $this->trails = array_filter($trails);
        }
        else if ($fallback) {
            $this->home = $fallback[0];

            array_shift($fallback);
            $this->trails = array_filter($fallback);
        }
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