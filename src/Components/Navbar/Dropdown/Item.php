<?php

namespace Jiannius\Atom\Components\Navbar\Dropdown;

use Illuminate\View\Component;

class Item extends Component
{
    public $badge;

    /**
     * Contructor
     */
    public function __construct($badge = null)
    {
        $this->badge = [
            'text' => is_array($badge) ? data_get($badge, 'text') : $badge,
            'color' => is_array($badge) ? data_get($badge, 'color') : 'theme',
            'colors' => [
                'gray' => 'bg-gray-600 text-white',
                'red' => 'bg-red-500 text-white',
                'green' => 'bg-green-500 text-white',
                'yellow' => 'bg-yellow-300 text-gray-900',
                'theme' => 'bg-theme text-theme-inverted',
                'black' => 'bg-black text-white',
            ],
        ];
    }

    /**
     * Render
     */
    public function render()
    {
        return view('atom::components.navbar.dropdown.item');
    }
}