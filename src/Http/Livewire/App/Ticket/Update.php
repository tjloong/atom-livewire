<?php

namespace Jiannius\Atom\Http\Livewire\App\Ticket;

use Livewire\Component;
use Jiannius\Atom\Models\Ticket;

class Update extends Component
{
    public Ticket $ticket;
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
    public function mount()
    {
        breadcrumb($this->ticket->number);
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
        return route('ticket.listing');
    }

    /**
     * Render
     */
    public function render()
    {
        return view('atom::app.ticket.update');
    }
}