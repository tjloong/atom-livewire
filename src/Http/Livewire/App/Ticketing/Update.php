<?php

namespace Jiannius\Atom\Http\Livewire\App\Ticketing;

use Jiannius\Atom\Traits\Livewire\WithPopupNotify;
use Livewire\Component;

class Update extends Component
{
    use WithPopupNotify;

    public $ticket;

    /**
     * Validation rules
     */
    protected function rules()
    {
        return [
            'ticket.status' => 'required',
        ];
    }

    /**
     * Mount
     */
    public function mount($ticketId)
    {
        $this->ticket = model('ticket')->findOrFail($ticketId);

        $this->ticket->comments()
            ->where('created_by', '<>', auth()->user()->id)
            ->update(['is_read' => true]);

        breadcrumbs()->push($this->ticket->number);
    }

    /**
     * Updated ticket status
     */
    public function updatedTicketStatus()
    {
        $this->ticket->save();
        $this->popup('Ticket Updated.');
    }

    /**
     * Delete
     */
    public function delete()
    {
        $this->ticket->delete();

        return redirect()->route('app.ticketing.listing');
    }

    /**
     * Render
     */
    public function render()
    {
        return atom_view('app.ticketing.update');
    }
}