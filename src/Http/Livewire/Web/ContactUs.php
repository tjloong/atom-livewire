<?php

namespace Jiannius\Atom\Http\Livewire\Web;

use Illuminate\Support\Facades\Notification;
use Jiannius\Atom\Component;
use Jiannius\Atom\Notifications\EnquiryNotification;
use Jiannius\Atom\Traits\Livewire\WithForm;

class ContactUs extends Component
{
    use WithForm;

    public $ref;
    public $utm;
    public $thank;
    public $enquiry;

    // validation
    protected function validation() : array
    {
        return [
            'enquiry.name' => ['required' => 'Your name is required.'],
            'enquiry.phone' => ['required' => 'Phone number is required.'],
            'enquiry.email' => ['required' => 'Email is required.'],
            'enquiry.message' => ['required' => 'Message is required.'],
        ];
    }

    // mount
    public function mount($slug = null) : void
    {
        $this->ref = request()->query('ref');
        $this->utm = request()->query('utm');
        $this->thank = $slug === 'thank';
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
            Notification::route('mail', $to)
                ->notify(new EnquiryNotification($enquiry));
        }
        
        return to_route('web.contact-us', 'thank');
    }
}