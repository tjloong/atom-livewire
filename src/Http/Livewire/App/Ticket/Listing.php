<?php

namespace Jiannius\Atom\Http\Livewire\App\Ticket;

use Livewire\Component;
use Livewire\WithPagination;
use Jiannius\Atom\Models\Ticket;

class Listing extends Component
{
    use WithPagination;

    public $user;
    public $search;
    public $sortBy = 'created_at';
    public $sortOrder = 'desc';

    protected $queryString = [
        'search', 
        'sortBy' => ['except' => 'created_at'],
        'sortOrder' => ['except' => 'asc'],
        'page' => ['except' => 1],
    ];

    /**
     * Mount
     * 
     * @return void
     */
    public function mount()
    {
        //
    }

    /**
     * Rendering livewire view
     * 
     * @return Response
     */
    public function render()
    {
        return view('atom::app.ticket.listing', ['tickets' => $this->getTickets()]);
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

    /**
     * Get tickets
     * 
     * @return Paginate
     */
    public function getTickets()
    {
        return Ticket::query()
            ->when($this->search, fn($q) => $q->search($this->search))
            ->when($this->user, fn($q) => $q->where('created_by', $this->user->id))
            ->orderBy($this->sortBy, $this->sortOrder)
            ->paginate(30);
    }
}