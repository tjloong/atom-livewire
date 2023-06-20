<?php

namespace Jiannius\Atom\Notifications\Email;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class SendNotification extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(
        public $email
    ) {
        //
    }

    /**
     * Get the notification's delivery channels.
     */
    public function via(mixed $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(mixed $notifiable): \Illuminate\Notifications\Messages\MailMessage
    {
        $view = collect([
            'email.email',
            'atom::email.email',
        ])->first(fn($name) => view()->exists($name));

        return (new MailMessage)
            ->from(data_get($this->email, 'from.email'), data_get($this->email, 'from.name'))
            ->replyTo(data_get($this->email, 'from.email'), data_get($this->email, 'from.name'))
            ->cc($this->email->cc)
            ->bcc($this->email->bcc)
            ->subject($this->email->subject)
            ->markdown($view, ['body' => $this->email->body]);
    }

    /**
     * Get the array representation of the notification.
     */
    public function toArray(mixed $notifiable): array
    {
        return [
            //
        ];
    }
}
