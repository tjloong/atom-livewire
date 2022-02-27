<?php

namespace Jiannius\Atom\Http\Livewire\Web;

use Livewire\Component;
use Illuminate\Support\Facades\Notification;
use Jiannius\Atom\Models\Enquiry;
use Jiannius\Atom\Models\SiteSetting;
use Jiannius\Atom\Notifications\EnquiryNotification;

class Contact extends Component
{
    public $contact;
    public $enquiry;
    public $gmapApi;

    protected $rules = [
        'enquiry.name' => 'required',
        'enquiry.phone' => 'required',
        'enquiry.email' => 'required',
        'enquiry.message' => 'required',
        'enquiry.status' => 'required',
    ];

    protected $messages = [
        'enquiry.name.required' => 'Your name is required.',
        'enquiry.phone.required' => 'Phone number is required.',
        'enquiry.email.required' => 'Email is required.',
        'enquiry.message.required' => 'Message is required.',
    ];

    /**
     * Mount event
     * 
     * @return void
     */
    public function mount()
    {
        // prevent bot
        if (!request()->query('ref')) return redirect()->route('home');
        else {
            $this->contact = [
                'phone' => SiteSetting::getSetting('phone'),
                'email' => SiteSetting::getSetting('email'),
                'address' => SiteSetting::getSetting('address'),
            ];

            $this->gmapApi = SiteSetting::getSetting('gmap_api');
            $this->initEnquiry();
        }
    }

    /**
     * Rendering livewire view
     * 
     * @return Response
     */
    public function render()
    {
        return view('atom::web.contact')->layout('layouts.web');
    }

    /**
     * Save enquiry
     */
    public function save()
    {
        $this->resetValidation();
        $this->validate();

        $mail = ['to' => null, 'params' => null];

        if (enabled_module('enquiries')) {
            if (is_array($this->enquiry)) $this->enquiry = Enquiry::create($this->enquiry);
            else $this->enquiry->save();

            $settings = SiteSetting::email()->get();
            $mail['to'] = $settings->where('name', 'notify_to')->first()->value;
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
     * Initialize enquiry
     * 
     * @return void
     */
    public function initEnquiry()
    {
        $this->enquiry = enabled_module('enquiries')
            ? new Enquiry(['status' => 'pending'])
            : [
                'name' => null,
                'phone' => null,
                'email' => null,
                'message' => null,
                'status' => 'pending',
            ];
    }
}