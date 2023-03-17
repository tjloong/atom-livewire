<?php

namespace Jiannius\Atom\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class DocumentEmailNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public $email;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($email)
    {
        $this->email = $email;
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
        $mail = (new MailMessage)
            ->from(data_get($this->email, 'from.email'), data_get($this->email, 'from.name'))
            ->replyTo(data_get($this->email, 'from.email'), data_get($this->email, 'from.name'))
            ->cc($this->email->cc)
            ->bcc($this->email->bcc)
            ->subject($this->email->subject);

        if ($att = $this->getAttachment()) {
            $mail->attachData(data_get($att, 'data'), data_get($att, 'name'));
        }

        if ($view = collect(['email.document', 'atom::email.document'])->first(fn($val) => view()->exists($val))) {
            $mail->markdown($view, ['body' => $this->email->body]);
        }

        return $mail;
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

    /**
     * Get attachment
     */
    public function getAttachment()
    {
        $document = $this->email->document;
        $pdf = $document->pdf(true);
        $filename = str()->title($document->type).'-'.$document->number.'.pdf';

        return [
            'name' => $filename,
            'data' => $pdf->output(),
        ];
    }
}
