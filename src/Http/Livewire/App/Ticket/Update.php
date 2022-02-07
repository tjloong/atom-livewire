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
        return view('atom::app.ticket.update');
    }

    /**
     * Ticket status update handler
     * 
     * @return void
     */
    public function updatedTicketStatus()
    {
        $this->ticket->save();
        $this->dispatchBrowserEvent('toast', ['message' => 'Ticket Updated', 'type' => 'success']);
    }

    /**
     * Delete ticket
     * 
     * @return void
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
}