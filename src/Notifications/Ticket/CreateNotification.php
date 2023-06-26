<?php

namespace Jiannius\Atom\Notifications\Ticket;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class CreateNotification extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(
        public $ticket
    ) {
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
            ->subject('['.config('app.name').'] New Support Ticket #'.$this->ticket->number)
            ->greeting('Hello!')
            ->line('You received a new support ticket from '.$this->ticket->createdBy->name.'.')
            ->line('
                <span style="font-weight: bold">Subject:</span> '.$this->ticket->subject.'<br>
                <span style="font-weight: bold">Description:</span> '.str()->limit($this->ticket->description, 50).'
            ')
            ->action('View full reply', route('app.ticket.listing'));
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
