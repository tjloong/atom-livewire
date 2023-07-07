<?php

namespace Jiannius\Atom\Http\Livewire\App\Ticket;

use Jiannius\Atom\Traits\Livewire\WithBreadcrumbs;
use Jiannius\Atom\Traits\Livewire\WithForm;
use Jiannius\Atom\Traits\Livewire\WithPopupNotify;
use Livewire\Component;

class Update extends Component
{
    use WithBreadcrumbs;
    use WithForm;
    use WithPopupNotify;

    public $ticket;

    // validation
    protected function validation(): array
    {
        return [
            'ticket.status' => ['nullable'],
        ];
    }

    // mount
    public function mount($id): void
    {
        $this->ticket = model('ticket')->findOrFail($id);

        $this->ticket->comments()
            ->where('created_by', '<>', user('id'))
            ->update(['read_at' => now()]);
    }

    // updated ticket status
    public function updatedTicketStatus(): void
    {
        $this->ticket->save();

        $this->popup('Ticket Updated.');
    }

    // delete
    public function delete(): mixed
    {
        $this->ticket->delete();

        return to_route('app.ticket.listing');
    }

    // render
    public function render(): mixed
    {
        return atom_view('app.ticket.update');
    }
}