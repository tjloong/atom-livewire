<?php

namespace Jiannius\Atom\Notifications\Ticket;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class CommentNotification extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(
        public $comment
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
            ->subject('['.config('app.name').'] New Comment for Ticket #'.$this->comment->ticket->number)
            ->greeting('Hello!')
            ->line('You have 1 new response to your Support Ticket.')
            ->line('<span style="font-style: italic;">"' . $this->comment->body . '"</span>')
            ->line('
                <span style="font-weight: bold;">Subject:</span> '.$this->comment->ticket->subject.'<br>
                <span style="font-weight: bold;">Description:</span> '.str()->limit($this->comment->ticket->description, 50).'
            ')
            ->action('View full reply', route('app.ticket.update', [$this->comment->ticket_id]));
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
