<?php

namespace Jiannius\Atom\Http\Livewire\App\Blog;

use Jiannius\Atom\Traits\Livewire\WithTable;
use Livewire\Component;

class Listing extends Component
{
    use WithTable;

    public $sort = 'updated_at,desc';
    public $filters = [
        'search' => null,
        'status' => null,
    ];

    protected $queryString = [
        'filters' => ['except' => [
            'search' => null,
            'status' => null,
        ]], 
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
            ->orderBy($this->sortBy, $this->sortOrder)
            ->paginate($this->maxRows);
    }

    /**
     * Render
     */
    public function render()
    {
        return atom_view('app.blog.listing');
    }
}