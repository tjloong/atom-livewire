<?php

namespace Jiannius\Atom\Components;

use Illuminate\View\Component;

class CdnScripts extends Component
{
    public $scripts = [];

    /**
     * Constructor
     */
    public function __construct($scripts = [])
    {
        $cdn = [
            'floating-ui' => [
                'https://cdn.jsdelivr.net/npm/@floating-ui/core@1.0.0/dist/floating-ui.core.umd.min.js',
                'https://cdn.jsdelivr.net/npm/@floating-ui/dom@1.0.0/dist/floating-ui.dom.umd.min.js',
            ],
            'flatpickr' => [
                'https://cdn.jsdelivr.net/npm/flatpickr',
                'https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css',
            ],
            'dayjs' => [
                'https://cdn.jsdelivr.net/npm/dayjs@1.11.4/dayjs.min.js',
                'https://cdn.jsdelivr.net/npm/dayjs@1.11.4/plugin/utc.js',
                'https://cdn.jsdelivr.net/npm/dayjs@1.11.4/plugin/relativeTime.js',
            ],
            'social-share' => [
                'https://cdn.jsdelivr.net/npm/sharer.js@latest/sharer.min.js',
            ],
            'sortable' => [
                'https://cdn.jsdelivr.net/npm/sortablejs@latest/Sortable.min.js',
            ],
            'swiper' => [
                'https://cdn.jsdelivr.net/npm/swiper@8.3.1/swiper-bundle.min.js',
                'https://cdn.jsdelivr.net/npm/swiper@8.3.1/swiper-bundle.min.css',
            ],
            'chartjs' => [
                'https://cdn.jsdelivr.net/npm/chart.js@3.8.0/dist/chart.min.js',
            ],
            'colorpicker' => [
                'https://cdn.jsdelivr.net/npm/@simonwep/pickr/dist/themes/monolith.min.css',
                'https://cdn.jsdelivr.net/npm/@simonwep/pickr/dist/pickr.min.js',
            ],
            'clipboard' => [
                'https://cdn.jsdelivr.net/npm/clipboard@2.0.10/dist/clipboard.min.js',
            ],
            'ckeditor' => [
                '/ckeditor/ckeditor.js',
            ],
        ];

        $toload = array_merge([
            'alpinejs', 
            'floating-ui', 
            'flatpickr', 
            'dayjs',
        ], $scripts);

        foreach ($cdn as $key => $val) {
            if (in_array($key, $toload)) {
                $this->scripts = array_merge($this->scripts, $val);
            }
        }
    }

    /**
     * Render
     */
    public function render()
    {
        return view('atom::components.cdn-scripts');
    }
}