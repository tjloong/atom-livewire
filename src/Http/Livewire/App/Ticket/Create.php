<?php

namespace Jiannius\Atom\Http\Livewire\App\Ticket;

use Livewire\Component;
use Jiannius\Atom\Models\Ticket;

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
     * Mount event
     * 
     * @return void
     */
    public function mount()
    {
        $this->ticket = new Ticket(['status' => 'opened']);
    }

    /**
     * Rendering livewire view
     * 
     * @return Response
     */
    public function render()
    {
        return view('atom::app.ticket.create');
    }

    /**
     * Submit
     * 
     * @return void
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
     * 
     * @return string
     */
    public function redirectTo()
    {
        return route('ticket.listing');
    }
}