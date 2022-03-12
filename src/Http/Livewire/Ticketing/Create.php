<?php

namespace Jiannius\Atom\Http\Livewire\Ticketing;

use Livewire\Component;

class Create extends Component
{
    public $ticket;

    protected $rules = [
        'ticket.subject' => 'required',
        'ticket.description' => 'required',
        'ticket.status' => 'required',
    ];

    protected $messages = [
        'ticket.subject.required' => 'Ticket subject is required.',
        'ticket.description.required' => 'Ticket description is required.',
    ];

    /**
     * Mount
     */
    public function mount()
    {
        $this->ticket = model('ticket');
        $this->ticket->status = 'opened';

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

        session()->flash('flash', 'Ticket Created::success');
        return redirect($this->redirectTo());
    }

    /**
     * Redirect to
     */
    public function redirectTo()
    {
        return route('ticketing.listing');
    }

    /**
     * Render
     */
    public function render()
    {
        return view('atom::ticketing.create')->layout('layouts.ticketing');
    }
}