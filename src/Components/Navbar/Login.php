<?php

namespace Jiannius\Atom\Components\Navbar;

use Illuminate\View\Component;
use Illuminate\Support\Facades\Route;

class Login extends Component
{
    public $links = [];

    /**
     * Constructor
     */
    public function __construct(
        $placeholder = [
            'login' => 'Login',
            'register' => 'Register',
        ]
    ) {
        if (Route::has('login')) {
            array_push($this->links, [
                'href' => route('login'),
                'placeholder' => data_get($placeholder, 'login'),
                'button' => false,
            ]);
        }

        if (Route::has('register')) {
            array_push($this->links, [
                'href' => route('register', ['ref' => 'navbar']),
                'placeholder' => data_get($placeholder, 'register'),
                'button' => true,
            ]);
        }
    }

    /**
     * Render
     */
    public function render()
    {
        return view('atom::components.navbar.login');
    }
}