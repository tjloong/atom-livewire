<?php

namespace Jiannius\Atom\Http\Livewire\Ticketing;

use Livewire\Component;
use Livewire\WithPagination;

class Listing extends Component
{
    use WithPagination;

    public $sortBy = 'created_at';
    public $sortOrder = 'desc';
    public $filters = ['search' => ''];

    protected $queryString = [
        'filters', 
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
            ->when(auth()->user()->account, fn($q) => $q->where('created_by', auth()->id()))
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
        return view('atom::ticketing.listing', [
            'tickets' => $this->tickets->paginate(30),
        ])->layout('layouts.ticketing');
    }
}