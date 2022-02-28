<?php

namespace Jiannius\Atom\Http\Livewire\App\Ticket;

use Livewire\Component;
use Livewire\WithPagination;
use Jiannius\Atom\Models\Ticket;

class Listing extends Component
{
    use WithPagination;

    public $user;
    public $sortBy = 'created_at';
    public $sortOrder = 'desc';
    public $filters = [
        'search' => '',
    ];

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
        return Ticket::query()
            ->when($this->user, fn($q) => $q->where('created_by', $this->user->id))
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
        return view('atom::app.ticket.listing', ['tickets' => $this->tickets->paginate(30)]);
    }
}