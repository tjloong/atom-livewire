<?php

namespace Jiannius\Atom\Http\Livewire\App\Page;

use Livewire\Component;
use Livewire\WithPagination;

class Listing extends Component
{
    use WithPagination;

    public $sortBy = 'updated_at';
    public $sortOrder = 'desc';
    public $filters = ['search' => ''];

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
        breadcrumbs()->home('Site Pages');
    }

    /**
     * Get pages property
     */
    public function getPagesProperty()
    {
        return model('page')
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
        return view('atom::app.page.listing', [
            'pages' => $this->pages->paginate(30),
        ]);
    }
}