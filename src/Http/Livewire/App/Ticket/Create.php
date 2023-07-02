<?php

namespace Jiannius\Atom\Http\Livewire\App\Ticket;

use Jiannius\Atom\Traits\Livewire\WithForm;
use Livewire\Component;

class Create extends Component
{
    use WithForm;

    public $ticket;

    /**
     * Validation
     */
    protected function validation(): array
    {
        return [
            'ticket.subject' => ['required' => 'Ticket subject is required.'],
            'ticket.description' => ['required' => 'Ticket description is required.'],
            'ticket.status' => ['required' => 'Ticket status is required.'],
        ];
    }

    /**
     * Mount
     */
    public function mount(): void
    {
        $this->ticket = model('ticket')->fill(['status' => 'pending']);

        breadcrumbs()->push('Create Ticket');
    }

    /**
     * Submit
     */
    public function submit(): mixed
    {
        $this->validateForm();

        $this->ticket->save();
        $this->ticket->notify();

        return breadcrumbs()->back();
    }

    /**
     * Render
     */
    public function render(): mixed
    {
        return atom_view('app.ticket.create');
    }
}