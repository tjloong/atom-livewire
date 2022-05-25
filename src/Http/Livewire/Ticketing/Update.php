<?php

namespace Jiannius\Atom\Http\Livewire\Ticketing;

use Livewire\Component;

class Update extends Component
{
    public $layout;
    public $ticket;

    protected function rules()
    {
        return [
            'ticket.status' => 'required',
        ];
    }

    /**
     * Mount
     */
    public function mount($ticket)
    {
        $this->layout = current_route('app.*') ? 'app' : 'ticketing';
        $this->ticket = model('ticket')->findOrFail($ticket);

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

        return redirect()->route('ticketing.listing');
    }

    /**
     * Render
     */
    public function render()
    {
        return view('atom::ticketing.update')->layout('layouts.'.$this->layout);
    }
}