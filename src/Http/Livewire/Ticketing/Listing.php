<?php

namespace Jiannius\Atom\Http\Livewire\Ticketing;

use Livewire\Component;
use Livewire\WithPagination;

class Listing extends Component
{
    use WithPagination;

    public $layout;
    public $sortBy = 'created_at';
    public $sortOrder = 'desc';
    public $filters = [
        'search' => '',
        'status' => '',
    ];

    protected $queryString = [
        'filters' => ['except' => [
            'search' => '',
            'status' => '',
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
        $this->layout = current_route('app.*') ? 'app' : 'ticketing';
        breadcrumbs()->home('Support Tickets');
    }

    /**
     * Get tickets property
     */
    public function getTicketsProperty()
    {
        return model('ticket')
            ->when(
                !auth()->user()->isAccountType(['root', 'system']), 
                fn($q) => $q->where('created_by', auth()->id())
            )
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
        return view('atom::ticketing.listing')->layout('layouts.'.$this->layout);
    }
}