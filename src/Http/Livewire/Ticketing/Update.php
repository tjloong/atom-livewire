<?php

namespace Jiannius\Atom\Http\Livewire\Ticketing;

use Livewire\Component;

class Update extends Component
{
    public $ticket;
    public $statuses = [
        'opened',
        'closed',
    ];

    protected $rules = [
        'ticket.status' => 'required',
    ];

    /**
     * Mount
     */
    public function mount($id)
    {
        $this->ticket = model('ticket')->findOrFail($id);
        breadcrumbs()->push($this->ticket->number);
    }

    /**
     * Updated ticket status
     */
    public function updatedTicketStatus()
    {
        $this->ticket->save();
        $this->dispatchBrowserEvent('toast', ['message' => 'Ticket Updated', 'type' => 'success']);
    }

    /**
     * Delete
     */
    public function delete()
    {
        $this->ticket->delete();
        
        session()->flash('flash', 'Ticket Deleted');

        return redirect($this->redirectTo());
    }

    /**
     * Redirect to...
     * 
     * @return string
     */
    public function redirectTo()
    {
        return route('ticketing.listing');
    }

    /**
     * Render
     */
    public function render()
    {
        return view('atom::ticketing.update')->layout('layouts.ticketing');
    }
}