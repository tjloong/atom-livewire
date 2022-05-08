<?php

namespace Jiannius\Atom\Components\Builder;

use Illuminate\View\Component;

class Announcements extends Component
{
    public $interval;
    public $announcements;

    /**
     * Contructor
     * 
     * @return void
     */
    public function __construct(
        $interval = 3000,
        $announcements = []
    ) {
        $this->interval = $interval;
        $this->announcements = $announcements;

        if (!$this->announcements && !config('atom.static_site')) {
            $this->announcements = collect(json_decode(site_settings('announcements')))->where('is_active', true)->values()->all();
        }
    }

    /**
     * Render component
     * 
     * @return Response
     */
    public function render()
    {
        return view('atom::components.builder.announcements');
    }
}