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

class Generic extends Mailable
{
    use Queueable, SerializesModels;

    public $tracker;

    /**
     * Create a new message instance.
     */
    public function __construct(public $settings)
    {
        if (get($this->settings, 'track')) {
            $this->tracker = model('sendmail')->create([
                'subject' => get($this->settings, 'subject'),
                'data' => [
                    'tags' => get($this->settings, 'tags'),
                    'metadata' => get($this->settings, 'metadata'),
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
            from: new Address(
                get($this->settings, 'sender_email') ?? settings('sender_email') ?? settings('notify_from') ?? env('MAIL_FROM_ADDRESS'),
                get($this->settings, 'sender_name') ?? settings('sender_name') ?? settings('notify_from') ?? env('MAIL_FROM_NAME'),
            ),
            replyTo: collect([get($this->settings, 'reply_to')])->filter()->map(fn($val) => new Address($val))->values()->all(),
            subject: get($this->settings, 'subject'),
            tags: get($this->settings, 'tags', []),
            metadata: get($this->settings, 'metadata', []),
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        $view = get($this->settings, 'view');
        $markdown = get($this->settings, 'markdown');
        $with = [
            ...get($this->settings, 'with') ?? [],
            'logo' => get($this->settings, 'logo'),
        ];

        return $markdown
            ? new Content(markdown: $markdown, with: $with)
            : new Content(view: $view, with: $with);
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return collect(get($this->settings, 'attachments'))
            ->map(fn($data) => Attachment::fromPath(get($data, 'path'))->as(get($data, 'filename')))
            ->toArray();
    }
}
