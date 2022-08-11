<?php

namespace Jiannius\Atom\Http\Livewire\App\Ticketing;

use Livewire\Component;
use Livewire\WithPagination;

class Listing extends Component
{
    use WithPagination;

    public $sortBy = 'created_at';
    public $sortOrder = 'desc';
    public $filters = [
        'search' => '',
        'status' => null,
    ];

    protected $queryString = [
        'filters' => ['except' => [
            'search' => '',
            'status' => null,
        ]],
        'sortBy' => ['except' => 'created_at'],
        'sortOrder' => ['except' => 'desc'],
        'page' => ['except' => 1],
    ];

    /**
     * Mount
     */
    public function mount()
    {
        breadcrumbs()->home('Support Tickets');
    }

    /**
     * Get tickets property
     */
    public function getTicketsProperty()
    {
        return model('ticket')
            ->selectRaw('tickets.*, if (tickets.status = "new", 0, 1) as seq')
            ->when(
                !auth()->user()->isAccountType(['root', 'system']), 
                fn($q) => $q->where('created_by', auth()->id())
            )
            ->filter($this->filters)
            ->orderBy('seq')
            ->orderBy($this->sortBy, $this->sortOrder)
            ->paginate(50);
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
        return view('atom::app.ticketing.listing');
    }
}