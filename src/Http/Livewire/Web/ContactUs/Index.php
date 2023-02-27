<?php

namespace Jiannius\Atom\Http\Livewire\Web\ContactUs;

use Livewire\Component;
use Illuminate\Support\Facades\Notification;
use Jiannius\Atom\Notifications\EnquiryNotification;

class Index extends Component
{
    public $enquiry;

    /**
     * Validation rules
     */
    protected function rules()
    {
        return [
            'enquiry.name' => 'required',
            'enquiry.phone' => 'required',
            'enquiry.email' => 'required',
            'enquiry.message' => 'required',
        ];
    }

    /**
     * Validation messages
     */
    protected function messages()
    {
        return [
            'enquiry.name.required' => __('Your name is required.'),
            'enquiry.phone.required' => __('Phone number is required.'),
            'enquiry.email.required' => __('Email is required.'),
            'enquiry.message.required' => __('Message is required.'),
        ];
    }

    /**
     * Mount
     */
    public function mount()
    {
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
    public function getContactProperty()
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
    public function submit()
    {
        $this->resetValidation();
        $this->validate();

        $mail = ['to' => null, 'params' => null];

        if (enabled_module('enquiries')) {
            if (is_array($this->enquiry)) $this->enquiry = model('enquiry')->create($this->enquiry);
            else $this->enquiry->save();

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
        
        return redirect('/contact-us/thank-you');
    }

    /**
     * Render
     */
    public function render()
    {
        return atom_view('web.contact-us');
    }
}