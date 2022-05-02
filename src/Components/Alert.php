<?php

namespace Jiannius\Atom\Components;

use Illuminate\View\Component;

class Alert extends Component
{
    public $type;
    public $icon;
    public $color;
    public $errors;

    /**
     * Contructor
     * 
     * @return void
     */
    public function __construct(
        $type = 'info',
        $errors = null,
    ) {
        $this->errors = $errors;
        $this->type = $errors ? 'error' : $type;
        $this->icon = $this->getIcon();
        $this->color = $this->getColor();
    }

    /**
     * Render component
     * 
     * @return Response
     */
    public function render()
    {
        return view('atom::components.alert');
    }

    /**
     * Get icon
     * 
     * @return string
     */
    private function getIcon()
    {
        $icons = [
            'info' => 'info-circle',
            'error' => 'x-circle',
            'success' => 'check-circle',
            'warning' => 'error-circle',
        ];

        return $icons[$this->type];
    }

    /**
     * Get color
     * 
     * @return object
     */
    private function getColor()
    {
        $bg = [
            'info' => 'bg-blue-100',
            'error' => 'bg-red-100',
            'success' => 'bg-green-100',
            'warning' => 'bg-yellow-100',
        ];

        $title = [
            'info' => 'text-blue-800',
            'error' => 'text-red-800',
            'success' => 'text-green-800',
            'warning' => 'text-yellow-800',
        ];

        $icon = [
            'info' => 'text-blue-400',
            'error' => 'text-red-400',
            'success' => 'text-green-400',
            'warning' => 'text-orange-500',            
        ];

        $text = [
            'info' => 'text-blue-600',
            'error' => 'text-red-600',
            'success' => 'text-green-600',
            'warning' => 'text-orange-700',
        ];

        return (object)[
            'bg' => $bg[$this->type],
            'title' => $title[$this->type],
            'icon' => $icon[$this->type],
            'text' => $text[$this->type],
        ];
    }
}