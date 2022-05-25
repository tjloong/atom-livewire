<?php

namespace Jiannius\Atom\Components\Navbar\Dropdown;

use Illuminate\View\Component;

class Auth extends Component
{
    public $canBackToApp;

    /**
     * Contructor
     */
    public function __construct() 
    {
        $this->canBackToApp = !request()->is('app') 
            && !request()->is('app/') 
            && !request()->is('app/*') 
            && auth()->check()
            && auth()->user()->canAccessPortal('app');
    }

    /**
     * Render
     */
    public function render()
    {
        return view('atom::components.navbar.dropdown.auth');
    }
}