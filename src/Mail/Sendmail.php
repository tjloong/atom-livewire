<?php

namespace Jiannius\Atom\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Schema;

class Sendmail extends Mailable
{
    use Queueable, SerializesModels;

    public $tracker;

    /**
     * Create a new message instance.
     */
    public function __construct(public $email)
    {
        if (Schema::hasTable('sendmails')) {
            $this->tracker = model('sendmail')->create([
                'subject' => get($this->email, 'subject'),
                'data' => [
                    'tags' => get($this->email, 'tags'),
                    'metadata' => get($this->email, 'metadata'),
                ],
            ]);
        }
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            from: new Address(get($this->email, 'from.email'), get($this->email, 'from.name')),
            replyTo: [new Address(get($this->email, 'reply_to'))],
            subject: get($this->email, 'subject'),
            tags: get($this->email, 'tags', []),
            metadata: get($this->email, 'metadata', []),
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'atom::mail.sendmail',
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return collect(get($this->email, 'attachments'))
            ->map(fn($data) => Attachment::fromPath(get($data, 'path'))->as(get($data, 'filename')))
            ->toArray();
    }
}
