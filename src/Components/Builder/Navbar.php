<?php

namespace Jiannius\Atom\Components\Builder;

use Illuminate\View\Component;

class Navbar extends Component
{
    public $fixed;
    public $align;
    public $sticky;
    public $showAuth;
    public $backToApp;
    public $registerPlaceholder;

    /**
     * Contructor
     * 
     * @return void
     */
    public function __construct(
        $align = 'left',
        $fixed = false,
        $sticky = false,
        $showAuth = true,
        $registerPlaceholder = 'Sign Up'
    ) {
        $this->fixed = $fixed;
        $this->align = $align;
        $this->sticky = $sticky;
        $this->showAuth = $showAuth;
        $this->registerPlaceholder = $registerPlaceholder;
        $this->backToApp = !request()->is('app') 
            && !request()->is('app/') 
            && !request()->is('app/*') 
            && auth()->check()
            && auth()->user()->canAccessAppPortal();
    }

    /**
     * Render component
     * 
     * @return Response
     */
    public function render()
    {
        return view('atom::components.builder.navbar');
    }
}