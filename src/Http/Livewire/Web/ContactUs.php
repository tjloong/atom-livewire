<?php

namespace Jiannius\Atom\Http\Livewire\Web;

use Illuminate\Support\Facades\Mail;
use Jiannius\Atom\Component;
use Jiannius\Atom\Rules\Profanity;
use Jiannius\Atom\Traits\Livewire\WithForm;

class ContactUs extends Component
{
    use WithForm;

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

        $enquiry = model('enquiry')->create($this->enquiry);

        if ($to = settings('notify_to')) {
            Mail::to($to)->send(new \Jiannius\Atom\Mail\ReceiveEnquiry($enquiry));
        }
        
        return to_route('web.contact-us', 'thank');
    }
}