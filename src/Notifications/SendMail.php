<?php

namespace Jiannius\Atom\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Jiannius\Atom\Traits\Notilog;

class SendMail extends Notification implements ShouldQueue
{
    use Notilog;
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(
        public $parameters
    ) {
        $this->enableNotilog();
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
        $senderName = data_get($this->parameters, 'from.name');
        $senderEmail = data_get($this->parameters, 'from.email');
        $replyTo = data_get($this->parameters, 'reply_to');
        $cc = collect(data_get($this->parameters, 'cc'))->pluck('email')->filter()->toArray();
        $bcc = collect(data_get($this->parameters, 'bcc'))->pluck('email')->filter()->toArray();
        $subject = data_get($this->parameters, 'subject');
        $body = nl2br(data_get($this->parameters, 'body'));
        $tags = data_get($this->parameters, 'tags', []);
        $attachments = data_get($this->parameters, 'attachments', []);

        $mail = (new MailMessage)
            ->from($senderEmail, $senderName)
            ->replyTo($replyTo ?? $senderEmail)
            ->cc($cc)
            ->bcc($bcc)
            ->subject($subject)
            ->line($body);

        foreach ($tags as $tag) {
            $mail->tag($tag);
        }

        foreach ($attachments as $attachment) {
            $mail->attach(data_get($attachment, 'path'), [
                'as' => data_get($attachment, 'name', 'file'),
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
