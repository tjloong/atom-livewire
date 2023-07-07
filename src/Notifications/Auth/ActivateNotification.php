<?php

namespace Jiannius\Atom\Notifications\Auth;

use Illuminate\Auth\Passwords\PasswordBroker;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class ActivateNotification extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct()
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
        $url = url(route('password.reset', [
            'token' => app(PasswordBroker::class)->createToken($notifiable),
            'email' => $notifiable->email,
        ]));

        return (new MailMessage)
            ->subject('['.config('app.name').'] Activate Your Account')
            ->greeting('Hello!')
            ->line('Please click the button below to activate your account.')
            ->action('Activate Account', $url);
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
