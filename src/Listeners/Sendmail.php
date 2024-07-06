<?php

namespace Jiannius\Atom\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class Sendmail
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle($event) : void
    {
        if ($event instanceof \Illuminate\Mail\Events\MessageSending) $this->sending($event);
        if ($event instanceof \Illuminate\Mail\Events\MessageSent) $this->sent($event);
    }

    // sending
    public function sending($event) : void
    {
        if ($tracker = get($event->data, 'tracker')) {
            $tracker->fill([
                'subject' => $event->message->getSubject(),
                'status' => enum('sendmail.status', 'SENDING'),
                'data' => [
                    ...$tracker->data,
                    'from' => collect($event->message->getFrom())->map(fn($val) => $val->toString())->toArray(),
                    'reply_to' => collect($event->message->getReplyTo())->map(fn($val) => $val->toString())->toArray(),
                    'to' => collect($event->message->getTo())->map(fn($val) => $val->toString())->toArray(),
                    'cc' => collect($event->message->getCc())->map(fn($val) => $val->toString())->toArray(),
                    'bcc' => collect($event->message->getBcc())->map(fn($val) => $val->toString())->toArray(),
                    'priority' => $event->message->getPriority(),
                    'body' => $event->message->getHtmlBody(),
                    'attachments' => collect($event->message->getAttachments())->map(fn($attachment) => $attachment->getFilename())->toArray(),
                ],
            ])->save();
        }
    }

    // sent
    public function sent($event) : void
    {
        if ($tracker = get($event->data, 'tracker')) {
            $tracker->fill([
                'status' => enum('sendmail.status', 'SENT'),
            ])->save();
        }
    }

    // failed
    public function failed($event, $exception) : void
    {
        if ($tracker = get($event, 'tracker')) {
            $tracker->fill([
                'status' => enum('sendmail.status', 'FAILED'),
                'data' => [
                    ...$tracker->data,
                    'error' => $exception->getMessage(),
                ],
            ])->save();
        }
    }
}