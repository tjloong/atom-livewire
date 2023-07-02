<?php

namespace Jiannius\Atom\Http\Livewire\App\Ticket;

use Jiannius\Atom\Traits\Livewire\WithForm;
use Jiannius\Atom\Traits\Livewire\WithPopupNotify;
use Livewire\Component;

class Update extends Component
{
    use WithForm;
    use WithPopupNotify;

    public $ticket;

    /**
     * Validation
     */
    protected function validation(): array
    {
        return [
            'ticket.status' => ['nullable'],
        ];
    }

    /**
     * Mount
     */
    public function mount($ticketId): void
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
    public function updatedTicketStatus(): void
    {
        $this->ticket->save();
        $this->popup('Ticket Updated.');
    }

    /**
     * Delete
     */
    public function delete(): mixed
    {
        $this->ticket->delete();

        return breadcrumbs()->back();
    }

    /**
     * Render
     */
    public function render(): mixed
    {
        return atom_view('app.ticket.update');
    }
}