<?php

namespace Jiannius\Atom\Components;

use App\Models\SiteSetting;
use Illuminate\View\Component;

class Gtm extends Component
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
            $this->id = $settings->where('name', 'gtm_id')->first()->value;
        }

        if (config('atom.gtm_id')) $this->id = config('atom.gtm_id');
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\View\View|\Closure|string
     */
    public function render()
    {
        return view('atom::components.gtm');
    }
}