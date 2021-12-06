<?php

namespace App\Http\Livewire\App\Banner;

use App\Models\Banner;
use Livewire\Component;
use Livewire\WithPagination;

class Listing extends Component
{
    use WithPagination;

    public $search;
    public $sortBy = 'start_at';
    public $sortOrder = 'desc';

    protected $queryString = [
        'search', 
        'sortBy' => ['except' => 'start_at'],
        'sortOrder' => ['except' => 'desc'],
        'page' => ['except' => 1],
    ];

    /**
     * Rendering livewire view
     * 
     * @return Response
     */
    public function render()
    {
        return view('livewire.app.banner.listing', [
            'banners' => Banner::query()
                ->when($this->search, fn($q) => $q->search($this->search))
                ->orderBy($this->sortBy, $this->sortOrder)
                ->paginate(30),
        ]);
    }

    /**
     * Updating search property
     * 
     * @return void
     */
    public function updatingSearch()
    {
        $this->resetPage();
    }
}