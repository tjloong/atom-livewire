<?php

namespace Jiannius\Atom\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class TicketCommentNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public $comment;

    /**
     * Create a new notification instance.
     *
     * @param TicketComment $comment
     * @param array $cc
     * @return void
     */
    public function __construct($comment)
    {
        $this->comment = $comment;
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
            ->subject('['.config('app.name').'] New Comment for Ticket #'.$this->comment->ticket->number)
            ->greeting('Hello!')
            ->line('You have 1 new response to your Support Ticket.')
            ->line('<span style="font-style: italic;">"' . $this->comment->body . '"</span>')
            ->line('
                <span style="font-weight: bold;">Subject:</span> '.$this->comment->ticket->subject.'<br>
                <span style="font-weight: bold;">Description:</span> '.str()->limit($this->comment->ticket->description, 50).'
            ')
            ->action('View full reply', route('ticketing.update', [$this->comment->ticket_id]));
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
