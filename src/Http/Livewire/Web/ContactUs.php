<?php

namespace Jiannius\Atom\Http\Livewire\Web;

use Jiannius\Atom\Atom;
use Jiannius\Atom\Rules\Profanity;
use Jiannius\Atom\Traits\Livewire\AtomComponent;
use Livewire\Component;

class ContactUs extends Component
{
    use AtomComponent;

    public $slug;
    public $thank;
    public $enquiry;

    protected function validation() : array
    {
        return [
            'enquiry.name' => ['required' => 'Your name is required.'],
            'enquiry.phone' => ['required' => 'Phone number is required.'],
            'enquiry.email' => ['required' => 'Email is required.'],
            'enquiry.message' => [
                'required' => 'Message is required.',
                new Profanity,
            ],
        ];
    }

    public function mount()
    {
        $this->thank = $this->slug === 'thank';
        $this->enquiry = [
            'name' => null,
            'phone' => null,
            'email' => null,
            'message' => null,
        ];
    }

    public function submit() : mixed
    {
        $this->validate();

        $enquiry = model('enquiry')->create($this->enquiry);

        if ($to = settings('notify_to')) {
            Atom::mail(
                to: (array) $to,
                subject: '['.config('app.name').'] New enquiry from '.$enquiry->name,
                markdown: 'atom::mail.receive-enquiry',
                with: ['enquiry' => $enquiry],
            );
        }
        
        return to_route('web.contact-us', 'thank');
    }
}