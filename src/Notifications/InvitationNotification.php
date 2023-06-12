<?php

namespace Jiannius\Atom\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class InvitationNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public $invitation;
    public $by;
    public $join;

    /**
     * Create a new notification instance.
     */
    public function __construct($invitation)
    {
        $this->invitation = $invitation;
        $this->by = optional($this->invitation->createdBy)->name ?? 'User';
        $this->join = $this->invitation->usesHasTenant && $this->invitation->tenant
            ? $this->invitation->tenant->name
            : config('app.name');

    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('['.config('app.name').'] '.$this->by.' has invited you to join '.$this->join)
            ->greeting($this->by.' has invited you to join '.$this->join)
            ->line($this->by.' has invited you to join '.$this->join.' on '.config('app.name'))
            ->action('Join '.$this->join, url('/invitation/pending'));
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}
