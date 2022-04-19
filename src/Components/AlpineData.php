<?php

namespace Jiannius\Atom\Components;

use Illuminate\View\Component;

class AlpineData extends Component
{
    public $scripts;

    /**
     * Contructor
     * 
     * @return void
     */
    public function __construct($scripts = [
        'table',
        'ic-input',
        'date-input',
        'phone-input',
        'image-input',
        'picker-input',
        'richtext-input',
    ]) {
        $this->scripts = array_filter($scripts);
    }

    /**
     * Render component
     * 
     * @return Response
     */
    public function render()
    {
        return view('atom::components.alpine-data');
    }
}