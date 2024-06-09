<?php

namespace Jiannius\Atom\Http\Livewire\Web;

use Illuminate\Support\Facades\Notification;
use Jiannius\Atom\Component;
use Jiannius\Atom\Rules\Profanity;
use Jiannius\Atom\Traits\Livewire\WithForm;

class ContactUs extends Component
{
    use WithForm;

    public $ref;
    public $utm;
    public $slug;
    public $thank;
    public $enquiry;

    // validation
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

    // mount
    public function mount()
    {
        $this->ref = request()->query('ref');
        $this->utm = request()->query('utm');
        $this->thank = $this->slug === 'thank';
        $this->enquiry = [
            'name' => null,
            'phone' => null,
            'email' => null,
            'message' => null,
        ];
    }

    // submit
    public function submit() : mixed
    {
        $this->validateForm();

        $enquiry = model('enquiry')->create(array_merge($this->enquiry, [
            'ref' => $this->ref,
            'utm' => $this->utm,
        ]));

        if ($to = settings('notify_to')) {
            Notification::route('mail', $to)->notify(
                new \Jiannius\Atom\Notifications\ContactUs($enquiry)
            );
        }
        
        return to_route('web.contact-us', 'thank');
    }
}