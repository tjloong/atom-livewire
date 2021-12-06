<?php

namespace App\Http\Livewire\App\Blog;

use App\Models\Blog;
use Livewire\Component;
use Livewire\WithPagination;

class Listing extends Component
{
    use WithPagination;

    public $search;
    public $sortBy = 'updated_at';
    public $sortOrder = 'desc';
    public $filterStatus = 'all';

    protected $queryString = [
        'search', 
        'sortBy' => ['except' => 'updated_at'],
        'sortOrder' => ['except' => 'desc'],
        'page' => ['except' => 1],
        'filterStatus' => ['except' => 'all'],
    ];

    /**
     * Rendering livewire view
     * 
     * @return Response
     */
    public function render()
    {
        return view('livewire.app.blog.listing', [
            'blogs' => Blog::query()
                ->when($this->search, fn($q) => $q->search($this->search))
                ->filter([
                    'status' => $this->filterStatus,
                ])
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