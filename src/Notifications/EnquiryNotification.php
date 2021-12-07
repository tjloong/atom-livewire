<?php

namespace Jiannius\Atom\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class EnquiryNotification extends Notification
{
    use Queueable;

    public $enquiry;

    /**
     * Create a new notification instance.
     *
     * @param Enquiry $enquiry
     * @param array $cc
     * @return void
     */
    public function __construct($enquiry)
    {
        $this->enquiry = $enquiry;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('[' . config('app.name') . '] New enquiry from ' . $this->enquiry->name)
            ->greeting('Hello!')
            ->line('You have a new enquiry:')
            ->line('
                <span style="font-weight: bold">Name:</span> ' . $this->enquiry->name . '<br>
                <span style="font-weight: bold">Phone:</span> ' . $this->enquiry->phone . '<br>
                <span style="font-weight: bold">Email:</span> ' . $this->enquiry->email . '<br>
                <span style="font-weight: bold">Message:</span><br>' . nl2br($this->enquiry->message) . '
            ');
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
