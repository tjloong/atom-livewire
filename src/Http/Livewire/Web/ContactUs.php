<?php

namespace Jiannius\Atom\Http\Livewire\Web;

use Livewire\Component;
use Illuminate\Support\Facades\Notification;
use Jiannius\Atom\Notifications\EnquiryNotification;
use Jiannius\Atom\Traits\Livewire\WithForm;

class ContactUs extends Component
{
    use WithForm;

    public $ref;
    public $enquiry;

    /**
     * Validation
     */
    protected function validation(): array
    {
        return [
            'enquiry.name' => ['required' => 'Your name is required.'],
            'enquiry.phone' => ['required' => 'Phone number is required.'],
            'enquiry.email' => ['required' => 'Email is required.'],
            'enquiry.message' => ['required' => 'Message is required.'],
        ];
    }

    /**
     * Mount
     */
    public function mount(): void
    {
        $this->ref = request()->query('ref');
        $this->enquiry = enabled_module('enquiries')
            ? model('enquiry')
            : [
                'name' => null,
                'phone' => null,
                'email' => null,
                'message' => null,
            ];
    }

    /**
     * Get contact property
     */
    public function getContactProperty(): array
    {
        $contact = config('atom.static_site')
            ? config('atom.contact')
            : [
                'phone' => settings('phone'),
                'email' => settings('email'),
                'address' => settings('address'),
                'gmap_url' => settings('gmap_url'),
            ];

        return array_filter($contact);
    }

    /**
     * Submit
     */
    public function submit(): mixed
    {
        $this->validateForm();

        $mail = ['to' => null, 'params' => null];

        if (enabled_module('enquiries')) {
            if (is_array($this->enquiry)) $this->enquiry = model('enquiry')->create($this->enquiry);
            else $this->enquiry->save();

            if ($this->ref) $this->enquiry->fill(['data' => ['ref' => $this->ref]])->save();

            $mail['to'] = settings('notify_to');
            $mail['params'] = $this->enquiry;
        }
        else {
            $mail['to'] = env('NOTIFY_TO');
            $mail['params'] = (object)$this->enquiry;
        }

        if ($mail['to']) {
            Notification::route('mail', $mail['to'])->notify(new EnquiryNotification($mail['params']));
        }
        
        return redirect()->route('web.thank', ['enquiry']);
    }

    /**
     * Render
     */
    public function render(): mixed
    {
        return atom_view('web.contact-us');
    }
}