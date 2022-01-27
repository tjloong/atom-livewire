<?php

namespace Jiannius\Atom\Http\Livewire\Web;

use Livewire\Component;
use Illuminate\Support\Facades\Notification;
use Jiannius\Atom\Models\Enquiry;
use Jiannius\Atom\Models\SiteSetting;
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
                $this->enquiry = enabled_feature('enquiries')
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
        $mail = ['to' => null, 'params' => null];

        if (enabled_feature('enquiries')) {
            $this->enquiry->save();

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
        
        return redirect()->route('contact', ['thank-you']);    
    }
}