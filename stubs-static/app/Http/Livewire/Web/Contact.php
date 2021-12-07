<?php

namespace App\Http\Livewire\Web;

use Livewire\Component;
use Illuminate\Support\Facades\Notification;
use Jiannius\Atom\Notifications\EnquiryNotification;

class Contact extends Component
{
    public $isSent;
    public $enquiry;

    protected $rules = [
        'enquiry.name' => 'required',
        'enquiry.phone' => 'required',
        'enquiry.email' => 'required',
        'enquiry.message' => 'required',
        'enquiry.status' => 'required',
    ];

    /**
     * Mount event
     * 
     * @return void
     */
    public function mount($slug = null)
    {
        
        if ($slug === 'thank-you') $this->isSent = true;
        else {
            // prevent bot
            if (!request()->query('ref')) return redirect()->route('home');
            else {
                $this->enquiry = [
                    'name' => null,
                    'phone' => null,
                    'email' => null,
                    'message' => null,
                    'status' => 'pending',
                ];
            }
        }
    }

    /**
     * Rendering livewire view
     * 
     * @return Response
     */
    public function render()
    {
        return view('livewire.web.contact')->layout('layouts.web');
    }

    /**
     * Save enquiry
     */
    public function save()
    {
        $to = env('NOTIFY_TO');

        if ($to) Notification::route('mail', $to)->notify(new EnquiryNotification((object)$this->enquiry));
        
        return redirect()->route('contact', ['thank-you']);
    }
}