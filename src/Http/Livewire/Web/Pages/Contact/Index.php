<?php

namespace Jiannius\Atom\Http\Livewire\Web\Pages\Contact;

use Livewire\Component;
use Illuminate\Support\Facades\Notification;
use Jiannius\Atom\Notifications\EnquiryNotification;

class Index extends Component
{
    public $ref;
    public $enquiry;

    protected $queryString = ['ref'];

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
     * Prevent bot
     */
    public function dehydrate()
    {
        if (!$this->ref) return redirect('/');
    }

    /**
     * Get contact property
     */
    public function getContactProperty()
    {
        $contact = config('atom.static_site')
            ? config('atom.contact')
            : [
                'phone' => site_settings('phone'),
                'email' => site_settings('email'),
                'address' => site_settings('address'),
                'gmap_url' => site_settings('gmap_url'),
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

            $mail['to'] = site_settings('notify_to');
            $mail['params'] = $this->enquiry;
        }
        else {
            $mail['to'] = env('NOTIFY_TO');
            $mail['params'] = (object)$this->enquiry;
        }

        if ($mail['to']) {
            Notification::route('mail', $mail['to'])->notify(new EnquiryNotification($mail['params']));
        }
        
        return redirect()->route('page', ['contact/thank-you']);
    }

    /**
     * Render
     */
    public function render()
    {
        return view('atom::web.pages.contact.index')->layout('layouts.web');
    }
}