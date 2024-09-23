<?php

namespace Jiannius\Atom\Traits\Models;

use Illuminate\Database\Eloquent\Relations\HasMany;

trait PushNotifiable
{
    // get push notifications for user
    public function push_notifications() : HasMany
    {
        return $this->hasMany(model('push-notification'), 'receiver_id');
    }

    // push notify user
    public function pushNotify($content, $data = []) : void
    {
        $message = model('push-notification')->create([
            'content' => $content,
            'sender_id' => user('id'),
            'receiver_id' => $this->id,
            ...$data,
        ]);

        // send to firebase

        // in app broadcast
        event(new \Jiannius\Atom\Events\PushNotificationCreated($message));
    }
}