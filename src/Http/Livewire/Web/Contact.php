<?php

namespace Jiannius\Atom\Http\Livewire\Web;

use Livewire\Component;
use Illuminate\Support\Facades\Notification;
use Jiannius\Atom\Notifications\EnquiryNotification;

class Contact extends Component
{
    public $contact;
    public $enquiry;

    protected $rules = [
        'enquiry.name' => 'required',
        'enquiry.phone' => 'required',
        'enquiry.email' => 'required',
        'enquiry.message' => 'required',
    ];

    protected $messages = [
        'enquiry.name.required' => 'Your name is required.',
        'enquiry.phone.required' => 'Phone number is required.',
        'enquiry.email.required' => 'Email is required.',
        'enquiry.message.required' => 'Message is required.',
    ];

    /**
     * Mount
     */
    public function mount()
    {
        // prevent bot
        if (!request()->query('ref')) return redirect()->route('home');
        else {
            $this->contact = [
                'phone' => site_settings('phone'),
                'email' => site_settings('email'),
                'address' => site_settings('address'),
                'gmap_url' => site_settings('gmap_url'),
            ];

            $this->initEnquiry();
        }
    }

    /**
     * Initialize enquiry
     */
    public function initEnquiry()
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
        
        return redirect()->route('contact.sent');
    }

    /**
     * Render
     */
    public function render()
    {
        return view('atom::web.contact')->layout('layouts.web');
    }
}