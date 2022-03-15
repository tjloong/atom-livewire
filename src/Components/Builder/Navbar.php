<?php

namespace Jiannius\Atom\Components\Builder;

use Illuminate\View\Component;

class Navbar extends Component
{
    public $home;
    public $lang;
    public $align;
    public $sticky;
    public $showAuth;
    public $registerPlaceholder;

    /**
     * Contructor
     * 
     * @return void
     */
    public function __construct(
        $align = 'left',
        $sticky = false,
        $showAuth = true,
        $registerPlaceholder = 'Sign Up',
        $lang = null
    ) {
        $this->lang = $lang;
        $this->align = $align;
        $this->sticky = $sticky;
        $this->showAuth = $showAuth;
        $this->registerPlaceholder = $registerPlaceholder;
        $this->home = $this->getHomeUrl();
    }

    /**
     * Get home url
     */
    public function getHomeUrl()
    {
        if (
            !request()->is('app') 
            && !request()->is('app/') 
            && !request()->is('app/*') 
            && auth()->id()
            && auth()->user()->canAccessAppPortal()
        ) return route('app.home');

        return null;
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