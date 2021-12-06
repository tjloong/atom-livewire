<?php

namespace Jiannius\Atom\Components\Table;

use Illuminate\View\Component;

class Button extends Component
{
    public $color;

    /**
     * Contructor
     * 
     * @return void
     */
    public function __construct($color = 'gray')
    {
        $this->color = $this->getColor($color);
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\View\View|\Closure|string
     */
    public function render()
    {
        return view('atom::components.table.button');
    }

    /**
     * Get color
     * 
     * @return string
     */
    private function getColor($color)
    {
        $colors = [
            'red' => 'text-red-500',
            'green' => 'text-green-500',
            'yellow' => 'text-yellow-500',
            'blue' => 'text-red-500',
            'gray' => 'text-gray-900'
        ];

        return $colors[$color];
    }
}