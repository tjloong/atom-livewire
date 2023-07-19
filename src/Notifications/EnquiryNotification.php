<?php

namespace Jiannius\Atom\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class EnquiryNotification extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(public $enquiry)
    {
        //
    }

    /**
     * Get the notification's delivery channels.
     */
    public function via($notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail($notifiable): \Illuminate\Notifications\Messages\MailMessage
    {
        return (new MailMessage)
            ->subject('['.config('app.name').'] New enquiry from '.data_get($this->enquiry, 'name'))
            ->greeting('Hello!')
            ->line('You have a new enquiry:')
            ->line('
                <span style="font-weight: bold">Name:</span> '.data_get($this->enquiry, 'name').'<br>
                <span style="font-weight: bold">Phone:</span> '.data_get($this->enquiry, 'phone').'<br>
                <span style="font-weight: bold">Email:</span> '.data_get($this->enquiry, 'email').'<br>
                <span style="font-weight: bold">Message:</span><br>'.nl2br(data_get($this->enquiry, 'message')).'
            ');
    }

    /**
     * Get the array representation of the notification.
     */
    public function toArray($notifiable): array
    {
        return [
            //
        ];
    }
}
