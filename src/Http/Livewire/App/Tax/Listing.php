<?php

namespace Jiannius\Atom\Http\Livewire\App\Tax;

use Livewire\Component;
use Livewire\WithPagination;

class Listing extends Component
{
    use WithPagination;

    public $sortBy = 'name';
    public $sortOrder = 'asc';
    public $filters = ['search' => ''];

    protected $queryString = [
        'filters' => ['except' => ['search' => '']], 
        'sortBy' => ['except' => 'name'],
        'sortOrder' => ['except' => 'asc'],
        'page' => ['except' => 1],
    ];

    /**
     * Mount
     */
    public function mount()
    {
        breadcrumbs()->home('Taxes');
    }

    /**
     * Get taxes property
     */
    public function getTaxesProperty()
    {
        return model('tax')
            ->when(model('tax')->enabledBelongsToAccountTrait, fn($q) => $q->belongsToAccount())
            ->filter($this->filters)
            ->orderBy($this->sortBy, $this->sortOrder)
            ->paginate(30);
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
        return view('atom::app.tax.listing');
    }
}