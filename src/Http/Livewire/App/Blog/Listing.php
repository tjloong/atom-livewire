<?php

namespace Jiannius\Atom\Http\Livewire\App\Blog;

use Livewire\Component;
use Livewire\WithPagination;

class Listing extends Component
{
    use WithPagination;

    public $sortBy = 'updated_at';
    public $sortOrder = 'desc';
    public $filters = [
        'search' => '',
        'status' => 'all',
    ];

    protected $queryString = [
        'filters', 
        'sortBy' => ['except' => 'updated_at'],
        'sortOrder' => ['except' => 'desc'],
        'page' => ['except' => 1],
    ];

    /**
     * Mount
     */
    public function mount()
    {
        breadcrumbs()->home('Blogs');
    }

    /**
     * Get blogs property
     */
    public function getBlogsProperty()
    {
        return model('blog')
            ->filter($this->filters)
            ->orderBy($this->sortBy, $this->sortOrder);
    }

    /**
     * Updated filters
     */
    public function updatedFilters()
    {
        $this->resetPage();
    }

    /**
     * Render
     */
    public function render()
    {
        return view('atom::app.blog.listing', [
            'blogs' => $this->blogs->paginate(30),
        ]);
    }
}