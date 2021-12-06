<?php

namespace App\Http\Livewire\App;

use App\Models\Blog;
use App\Models\Enquiry;
use Livewire\Component;

class Dashboard extends Component
{
    public $dateFrom;
    public $dateTo;

    public function mount()
    {
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
        return view('livewire.app.dashboard', [
            'sum' => [
                'blogs' => Blog::whereBetween('created_at', [$this->dateFrom, $this->dateTo])->count(),
                'published' => Blog::whereBetween('published_at', [$this->dateFrom, $this->dateTo])->count(),
                'enquiries' => Enquiry::whereBetween('created_at',[$this->dateFrom, $this->dateTo])->count(),
                'pending' => Enquiry::whereBetween('created_at',[$this->dateFrom, $this->dateTo])->where('status', 'pending')->count(),
            ],
        ]);
    }
}