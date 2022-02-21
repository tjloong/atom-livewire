<?php

namespace Jiannius\Atom\Http\Livewire\App;

use Livewire\Component;
use Jiannius\Atom\Models\Blog;
use Jiannius\Atom\Models\Enquiry;

class Dashboard extends Component
{
    public $dateFrom;
    public $dateTo;

    public function mount()
    {
        breadcrumb_home('Dashboard');
        
        $this->dateFrom = today()->subDays(30);
        $this->dateTo = today();
    }

    /**
     * Rendering livewire view
     * 
     * @return Response
     */
    public function render()
    {
        return view('atom::app.dashboard', [
            'sum' => [
                'blogs' => Blog::whereBetween('created_at', [$this->dateFrom, $this->dateTo])->count(),
                'published' => Blog::whereBetween('published_at', [$this->dateFrom, $this->dateTo])->count(),
                'enquiries' => Enquiry::whereBetween('created_at',[$this->dateFrom, $this->dateTo])->count(),
                'pending' => Enquiry::whereBetween('created_at',[$this->dateFrom, $this->dateTo])->where('status', 'pending')->count(),
            ],
        ]);
    }
}