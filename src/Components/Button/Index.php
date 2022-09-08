<?php

namespace Jiannius\Atom\Components\Button;

use Illuminate\View\Component;

class Index extends Component
{
    public $mode;
    public $icon;
    public $size;
    public $block;
    public $color;
    public $renderable;
    public $config;

    /**
     * Contructor
     * 
     * @return void
     */
    public function __construct(
        $icon = null,
        $size = 'base', 
        $color = 'theme',
        $block = false,
        $inverted = false, 
        $outlined = false,
        $hide = null,
        $can = null
    ) {
        $this->renderable = !$hide && (!$can || ($can && auth()->user()->can($can)));
        $this->icon = $icon;
        $this->size = $size;
        $this->color = $color;
        $this->block = $block;

        $this->mode = head(array_filter([
            $inverted ? 'inverted' : null,
            $outlined ? 'outlined' : null,
            !$inverted && !$outlined ? 'normal' : null,
        ]));

        $this->config = $this->getConfig();
    }

    /**
     * Get config
     */
    public function getConfig()
    {
        $color = [
            'theme' => [
                'normal' => 'bg-theme border-2 border-theme text-theme-inverted',
                'inverted' => 'text-theme hover:bg-theme hover:text-theme-inverted hover:border-2 hover:border-theme',
                'outlined' => 'bg-white border-2 border-theme text-theme',
            ],
            'theme-light' => [
                'normal' => 'bg-theme-light border-2 border-theme-light text-theme-inverted-light',
                'inverted' => 'text-theme-light hover:bg-theme-light hover:text-theme-inverted-light hover:border-2 hover:border-theme-light',
                'outlined' => 'bg-white border-2 border-theme-light text-theme-light',
            ],
            'theme-dark' => [
                'normal' => 'bg-theme-dark border-2 border-theme-dark text-theme-inverted-dark',
                'inverted' => 'text-theme-dark hover:bg-theme-dark hover:text-theme-inverted-dark hover:border-2 hover:border-theme-dark',
                'outlined' => 'bg-white border-2 border-theme-dark text-theme-dark',
            ],
            'green' => [
                'normal' => 'bg-green-500 border-2 border-green-500 text-white',
                'inverted' => 'border-2 border-transparent hover:bg-green-100 hover:border-green-100 text-green-500',
                'outlined' => 'bg-white border-2 border-green-500 text-green-500',
            ],
            'red' => [
                'normal' => 'bg-red-500 border-2 border-red-500 text-white',
                'inverted' => 'border-2 border-transparent hover:bg-red-100 hover:border-red-100 text-red-500',
                'outlined' => 'bg-white border-2 border-red-500 text-red-500',
            ],
            'blue' => [
                'normal' => 'bg-blue-500 border-2 border-blue-500 text-white',
                'inverted' => 'border-2 border-transparent hover:bg-blue-100 hover:border-blue-100 text-blue-500',
                'outlined' => 'bg-white border-2 border-blue-500 text-blue-500',
            ],
            'yellow' => [
                'normal' => 'bg-yellow-200 border-2 border-yellow-200 text-orange-700',
                'inverted' => 'border-2 border-transparent hover:bg-yellow-200 hover:border-yellow-200 text-orange-800',
                'outlined' => 'bg-white border-2 border-yellow-600 text-yellow-600',
            ],
            'gray' => [
                'normal' => 'bg-gray-200 border-2 border-gray-200 text-gray-800',
                'inverted' => 'border-2 border-transparent hover:bg-gray-100 hover:border-gray-100 text-gray-800',
                'outlined' => 'bg-white border-2 border-gray-800 text-gray-800',
            ],
        ];

        return json_decode(json_encode([
            'styles' => [
                'size' => [
                    'xs' => 'btn btn-xs',
                    'sm' => 'btn btn-sm',
                    'base' => 'btn btn-base',
                    'md' => 'btn btn-md',
                    'lg' => 'btn btn-lg',
                ][$this->size],

                'color' => $color[$this->color][$this->mode],
            ],
            'icon' => [
                'name' => is_string($this->icon) ? $this->icon : data_get($this->icon, 'name'),
                'size' => [
                    'xs' => '10px',
                    'sm' => '12px',
                    'base' => '14px',
                    'md' => '18px',
                    'lg' => '20px',
                ][$this->size],
                'position' => is_string($this->icon) ? 'left' : data_get($this->icon, 'position', 'left'),
            ],
        ]));
    }

    /**
     * Render component
     * 
     * @return Response
     */
    public function render()
    {
        return view('atom::components.button.index');
    }
}