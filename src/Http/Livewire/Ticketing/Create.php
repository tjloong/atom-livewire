<?php

namespace Jiannius\Atom\Http\Livewire\Ticketing;

use Livewire\Component;
use Illuminate\Support\Facades\Notification;
use Jiannius\Atom\Notifications\TicketCreateNotification;

class Create extends Component
{
    public $layout;
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
        $this->layout = current_route('app.*') ? 'app' : 'ticketing';
        $this->ticket = model('ticket')->fill(['status' => 'opened']);
        
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

        session()->flash('flash', 'Ticket Created::success');

        return redirect()->route('ticketing.listing');
    }

    /**
     * Render
     */
    public function render()
    {
        return view('atom::ticketing.create')->layout('layouts.'.$this->layout);
    }
}