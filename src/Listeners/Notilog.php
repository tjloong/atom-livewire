<?php

namespace Jiannius\Atom\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Events\NotificationSending;
use Illuminate\Notifications\Events\NotificationSent;
use Illuminate\Queue\InteractsWithQueue;

class Notilog
{
    public $channel;
    public $notifiable;
    public $notification;

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
        $this->channel = $event->channel;
        $this->notifiable = $event->notifiable;
        $this->notification = $event->notification;

        if ($event instanceof NotificationSending) {
            $this->sending();
        }

        if ($event instanceof NotificationSent) {
            $this->sent();
        }
    }

    // sending
    public function sending() : void
    {
        if ($this->notification->notilogEnabled ?? false) {
            $ulid = $this->notification->notilogUlid;

            if (!model('notilog')->where('ulid', $ulid)->count()) {
                $notilog = model('notilog')->create(array_merge([
                    'ulid' => $ulid,
                    'channel' => $this->channel,
                    'status' => enum('notilog.status', 'SENDING')->value,
                ], $this->getNotificationData()));
    
                if ($sender = $this->notification->notilogSender) {
                    $notilog->setFootprint('created', $sender)->save();
                }
            }
        }
    }

    // sent
    public function sent() : void
    {
        if ($ulid = $this->notification->notilogUlid ?? null) {
            $notilog = model('notilog')->findUlid($ulid);
            $notilog->fill(['status' => enum('notilog.status', 'SENT')])->save();
        }
    }

    // failed - will be call directly from the notification failed handler
    public static function failed($ulid, $error) : void
    {
        if ($notilog = model('notilog')->findUlid($ulid)) {
            $notilog->fill([
                'status' => enum('notilog.status', 'FAILED')->value,
                'data' => array_merge($notilog->data, ['error' => $error]),
            ])->save();
        }
    }

    // get notification data
    public function getNotificationData() : array
    {
        if ($this->channel === 'mail') {
            $message = $this->notification->toMail($this->notifiable);

            return [
                'subject' => $message->subject,
                'greeting' => $message->greeting,
                'body' => $message->render(),
                'tags' => $message->tags,
                'data' => [
                    'to' => $this->notifiable instanceof \App\Models\User
                        ? [$this->notifiable->email => $this->notifiable->name]
                        : data_get($this->notifiable, 'routes.mail'),
                    'level' => $message->level,
                    'from' => [data_get($message->from, 0) => data_get($message->from, 1)],
                    'reply_to' => collect($message->replyTo)->mapWithKeys(fn($val) => [
                        data_get($val, 0) => data_get($val, 1),
                    ]),
                    'cc' => collect($message->cc)->mapWithKeys(fn($val) => [
                        data_get($val, 0) => data_get($val, 1),
                    ]),
                    'bcc' => collect($message->bcc)->mapWithKeys(fn($val) => [
                        data_get($val, 0) => data_get($val, 1),
                    ]),
                    'priority' => $message->priority,
                    'metadata' => $message->metadata,
                    'attachments' => collect($message->attachments)->map(fn($val) => [
                        'name' => data_get($val, 'options.as'),
                        'path' => data_get($val, 'file'),
                    ]),
                ],
            ];
        }
    }
}