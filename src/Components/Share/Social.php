<?php

namespace Jiannius\Atom\Components\Share;

use Illuminate\View\Component;

class Social extends Component
{
    public $uid;
    public $sites;
    public $icons;
    public $titles;

    // contructor
    public function __construct($sites = null)
    {
        // refer https://ellisonleao.github.io/sharer.js for available sites
        $this->sites = $sites ?? [
            'facebook',
            'twitter',
            'linkedin',
            'whatsapp',
            'telegram',
            'email',
        ];

        $this->icons = [
            'facebook' => ['name' => 'brands facebook', 'color' => 'text-blue-500'],
            'twitter' => ['name' => 'brands twitter', 'color' => 'text-blue-400'],
            'linkedin' => ['name' => 'brands linkedin', 'color' => 'text-blue-400'],
            'whatsapp' => ['name' => 'brands whatsapp', 'color' => 'text-green-500'],
            'telegram' => ['name' => 'brands telegram', 'color' => 'text-blue-500'],
            'email' => ['name' => 'envelope', 'type' => 'regular'],
        ];

        $this->titles = [
            'linkedin' => 'Linked-In',
        ];
    }

    // render component
    public function render()
    {
        return view('atom::components.share.social');
    }
}