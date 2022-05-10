<?php

namespace Jiannius\Atom\Http\Livewire\App;

use Livewire\Component;

class Dashboard extends Component
{
    public $date;

    /**
     * Mount
     */
    public function mount()
    {
        breadcrumbs()->home('Dashboard');

        $this->date = [
            format_date(today()->startOfDay()->subDays(30), 'carbon')->toDateString(),
            format_date(now(), 'carbon')->toDateString(),
        ];
    }

    /**
     * Get blogs property
     */
    public function getBlogsProperty()
    {
        if (!enabled_module('blogs')) return;

        return [
            'count' => model('blog')->whereBetween('created_at', $this->date)->count(),
            'published' => model('blog')->whereBetween('published_at', $this->date)->count(),
        ];
    }

    /**
     * Get enquiries property
     */
    public function getEnquiriesProperty()
    {
        if (!enabled_module('enquiries')) return;

        return [
            'count' => model('enquiry')->whereBetween('created_at', $this->date)->count(),
            'pending' => model('enquiry')->whereBetween('created_at', $this->date)->where('status', 'pending')->count(),
        ];
    }

    /**
     * Render
     */
    public function render()
    {
        return view('atom::app.dashboard');
    }
}