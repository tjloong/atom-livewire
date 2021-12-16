<?php

namespace Jiannius\Atom\Components\Builder;

use Illuminate\View\Component;

class Navbar extends Component
{
    public $align;
    public $sticky;
    public $login;
    public $register;
    public $registerPlaceholder;

    /**
     * Contructor
     * 
     * @return void
     */
    public function __construct(
        $align = 'left',
        $sticky = false,
        $login = true,
        $register = true,
        $registerPlaceholder = 'Sign Up'
    ) {
        $this->align = $align;
        $this->sticky = $sticky;
        $this->login = $login;
        $this->register = $register;
        $this->registerPlaceholder = $registerPlaceholder;
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