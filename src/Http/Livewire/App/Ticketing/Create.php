<?php

namespace Jiannius\Atom\Http\Livewire\App\Ticketing;

use Livewire\Component;

class Create extends Component
{
    public $ticket;

    /**
     * Validation rules
     */
    protected function rules()
    {
        return [
            'ticket.subject' => 'required',
            'ticket.description' => 'required',
            'ticket.status' => 'required',
        ];
    }

    /**
     * Validation messages
     */
    protected function messages()
    {
        return [
            'ticket.subject.required' => 'Ticket subject is required.',
            'ticket.description.required' => 'Ticket description is required.',
        ];
    }

    /**
     * Mount
     */
    public function mount()
    {
        $this->ticket = model('ticket')->fill(['status' => 'pending']);
        breadcrumbs()->push('Create Ticket');
    }

    /**
     * Submit
     */
    public function submit()
    {
        $this->resetValidation();
        $this->validate();

        $this->ticket->save();
        $this->ticket->notify();

        return breadcrumbs()->back();
    }

    /**
     * Render
     */
    public function render()
    {
        return atom_view('app.ticketing.create');
    }
}