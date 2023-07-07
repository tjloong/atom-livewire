<?php

namespace Jiannius\Atom\Http\Livewire\App\Ticket;

use Jiannius\Atom\Traits\Livewire\WithBreadcrumbs;
use Jiannius\Atom\Traits\Livewire\WithForm;
use Livewire\Component;

class Create extends Component
{
    use WithBreadcrumbs;
    use WithForm;

    public $ticket;

    // validation
    protected function validation(): array
    {
        return [
            'ticket.subject' => ['required' => 'Ticket subject is required.'],
            'ticket.description' => ['required' => 'Ticket description is required.'],
            'ticket.status' => ['required' => 'Ticket status is required.'],
        ];
    }

    // mount
    public function mount(): void
    {
        $this->ticket = model('ticket')->fill(['status' => 'pending']);
    }

    // submit
    public function submit(): mixed
    {
        $this->validateForm();

        $this->ticket->save();
        $this->ticket->notify();

        return to_route('app.ticket.listing');
    }

    // render
    public function render(): mixed
    {
        return atom_view('app.ticket.create');
    }
}