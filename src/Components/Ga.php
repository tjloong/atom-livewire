<?php

namespace Jiannius\Atom\Components;

use Jiannius\Atom\Models\SiteSetting;
use Illuminate\View\Component;

class Ga extends Component
{
    public $id;
    public $noscript;

    /**
     * Create the component instance.
     *
     * @param boolean $noscript
     * @return void
     */
    public function __construct($noscript = false)
    {
        $this->noscript = $noscript;

        if (!config('atom.static_site')) {
            $settings = SiteSetting::tracking()->get();
            $this->id = $settings->where('name', 'ga_id')->first()->value ?? null;
        }

        if (config('atom.ga_id')) $this->id = config('atom.ga_id');
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\View\View|\Closure|string
     */
    public function render()
    {
        return view('atom::components.ga');
    }
}