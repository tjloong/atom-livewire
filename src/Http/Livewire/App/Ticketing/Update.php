<?php

namespace Jiannius\Atom\Http\Livewire\App\Ticketing;

use Livewire\Component;

class Update extends Component
{
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
        $this->ticket = model('ticket')->findOrFail($ticket);
        $this->ticket->comments()->where('created_by', '<>', auth()->id())->update(['is_read' => true]);

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
        return view('atom::app.ticketing.update');
    }
}