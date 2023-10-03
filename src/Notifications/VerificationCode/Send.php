<?php

namespace Jiannius\Atom\Notifications\VerificationCode;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class Send extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(
        public $verificationCode
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
        return (new MailMessage)
            ->subject(__('atom::auth.notification.verification.subject'))
            ->greeting(__('atom::auth.notification.verification.greeting'))
            ->line(__('atom::auth.notification.verification.content'))
            ->line('<h1>'.$this->verificationCode->code.'</h1>');
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
