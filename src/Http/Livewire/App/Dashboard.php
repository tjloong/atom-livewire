<?php

namespace Jiannius\Atom\Http\Livewire\App;

use Livewire\Component;

class Dashboard extends Component
{
    public $dateFrom;
    public $dateTo;

    /**
     * Mount
     */
    public function mount()
    {
        breadcrumbs()->home('Dashboard');
        
        $this->dateFrom = today()->subDays(30);
        $this->dateTo = today();
    }

    /**
     * Get blogs property
     */
    public function getBlogsProperty()
    {
        if (!enabled_module('blogs')) return;

        return [
            'count' => model('blog')->whereBetween('created_at', [$this->dateFrom, $this->dateTo])->count(),
            'published' => model('blog')->whereBetween('published_at', [$this->dateFrom, $this->dateTo])->count(),
        ];
    }

    /**
     * Get enquiries property
     */
    public function getEnquiriesProperty()
    {
        if (!enabled_module('enquiries')) return;

        return [
            'count' => model('enquiry')->whereBetween('created_at',[$this->dateFrom, $this->dateTo])->count(),
            'pending' => model('enquiry')->whereBetween('created_at',[$this->dateFrom, $this->dateTo])->where('status', 'pending')->count(),
        ];
    }

    /**
     * Rendering livewire view
     * 
     * @return Response
     */
    public function render()
    {
        return view('atom::app.dashboard', [
            'blogs' => $this->blogs,
            'enquiries' => $this->enquiries,
        ]);
    }
}