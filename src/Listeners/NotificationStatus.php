<?php

namespace Jiannius\Atom\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Events\NotificationSending;
use Illuminate\Notifications\Events\NotificationSent;
use Illuminate\Queue\InteractsWithQueue;

class NotificationStatus
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
        if ($this->notification->isNotificationTrackable ?? false) {
            $ulid = $this->notification->notificationTrackerUlid;

            if (!model('notification')->where('ulid', $ulid)->count()) {
                $tracker = model('notification')->create(array_merge([
                    'ulid' => $ulid,
                    'channel' => $this->channel,
                    'status' => enum('notification.status', 'PENDING')->value,
                ], $this->getTrackerData()));
    
                if ($sender = $this->notification->notificationTrackerSender) {
                    $tracker->setFootprint('created', $sender)->save();
                }
            }
        }
    }

    // sent
    public function sent() : void
    {
        if ($ulid = $this->notification->notificationTrackerUlid ?? null) {
            $tracker = model('notification')->findUlid($ulid);
            $tracker->fill(['status' => enum('notification.status', 'SENT')])->save();
        }
    }

    // failed - will be call directly from the notification failed handler
    public static function failed($ulid, $error) : void
    {
        if ($tracker = model('notification')->findUlid($ulid)) {
            $tracker->fill([
                'status' => enum('notification.status', 'FAILED')->value,
                'data' => array_merge($tracker->data, ['error' => $error]),
            ])->save();
        }
    }

    // get tracker data
    public function getTrackerData() : array
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