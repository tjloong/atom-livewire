<?php

namespace Jiannius\Atom\Components;

use Illuminate\View\Component;

class SocialShare extends Component
{
    public $uid;
    public $sites;
    public $icons;
    public $title;

    /**
     * Contructor
     * 
     * @return void
     */
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
            'facebook' => ['name' => 'facebook', 'color' => 'text-blue-500'],
            'twitter' => ['name' => 'twitter', 'color' => 'text-blue-400'],
            'linkedin' => ['name' => 'linkedin', 'color' => 'text-blue-400'],
            'whatsapp' => ['name' => 'whatsapp', 'color' => 'text-green-500'],
            'telegram' => ['name' => 'telegram', 'color' => 'text-blue-500'],
            'email' => ['name' => 'envelope', 'type' => 'regular'],
        ];

        $this->titles = [
            'linkedin' => 'Linked-In',
        ];
    }

    /**
     * Render component
     * 
     * @return Response
     */
    public function render()
    {
        return view('atom::components.social-share');
    }
}