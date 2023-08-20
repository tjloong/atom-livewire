<?php

namespace Jiannius\Atom\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class SendEmailNotification extends Notification implements ShouldQueue
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
        $mail = (new MailMessage)
            ->from(data_get($this->email, 'from.email'), data_get($this->email, 'from.name'))
            ->replyTo(data_get($this->email, 'from.email'), data_get($this->email, 'from.name'))
            ->cc(data_get($this->email, 'cc'))
            ->bcc(data_get($this->email, 'bcc'))
            ->subject(data_get($this->email, 'subject'))
            ->markdown(
                collect([
                    'email.email',
                    'atom::email.email',
                ])->first(fn($name) => view()->exists($name)), 
                ['body' => data_get($this->email, 'body')],
            );

        if ($attach = data_get($this->email, 'attachment')) {
            $mail->attach(data_get($attach, 'path'), [
                'as' => data_get($attach, 'name', 'file'),
            ]);
        }

        return $mail;
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
