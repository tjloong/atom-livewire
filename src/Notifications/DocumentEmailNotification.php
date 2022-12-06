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
        $view = $this->getView();
        $attachment = $this->getAttachment();

        return (new MailMessage)
            ->from(data_get($this->email, 'from.email'), data_get($this->email, 'from.name'))
            ->replyTo(data_get($this->email, 'from.email'), data_get($this->email, 'from.name'))
            ->cc($this->email->cc)
            ->bcc($this->email->bcc)
            ->subject($this->email->subject)
            ->attachData(data_get($attachment, 'data'), data_get($attachment, 'name'))
            ->markdown($view, ['body' => $this->email->body]);
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
     * Get view
     */
    public function getView()
    {
        if (file_exists(base_path('resources/views/emails.document'))) return 'emails.document';
        else return 'atom::emails.document';
    }

    /**
     * Get attachment
     */
    public function getAttachment()
    {
        $document = $this->email->document;
        $pdf = $document->pdf();
        $filename = str()->title($document->type).'-'.$document->number.'.pdf';

        return [
            'name' => $filename,
            'data' => $pdf->output(),
        ];
    }
}
