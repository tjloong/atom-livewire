<?php

namespace Jiannius\Atom\Http\Livewire\App\Blog;

use Livewire\Component;
use Livewire\WithPagination;
use Jiannius\Atom\Models\Blog;

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
        return view('atom::app.blog.listing', [
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