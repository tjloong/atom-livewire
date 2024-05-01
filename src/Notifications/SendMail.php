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
        $senderName = get($this->parameters, 'from.name');
        $senderEmail = get($this->parameters, 'from.email');
        $replyTo = get($this->parameters, 'reply_to');
        $cc = collect(get($this->parameters, 'cc'))->pluck('email')->filter()->toArray();
        $bcc = collect(get($this->parameters, 'bcc'))->pluck('email')->filter()->toArray();
        $subject = get($this->parameters, 'subject');
        $body = nl2br(get($this->parameters, 'body'));
        $tags = get($this->parameters, 'tags', []);
        $attachments = get($this->parameters, 'attachments', []);
        $mail = (new MailMessage);

        if (!empty($senderEmail)) $mail->from($senderEmail, $senderName);
        if (!empty($replyTo)) $mail->replyTo($replyTo);
        if ($cc) $mail->cc($cc);
        if ($bcc) $mail->bcc($bcc);

        $mail->subject($subject)->line($body);

        foreach ($tags as $tag) {
            $mail->tag($tag);
        }

        foreach ($attachments as $attachment) {
            $mail->attach(get($attachment, 'path'), [
                'as' => get($attachment, 'name', 'file'),
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
