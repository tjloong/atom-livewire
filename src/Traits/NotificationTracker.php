<?php

namespace Jiannius\Atom\Traits;

use Jiannius\Atom\Listeners\NotificationStatus;

trait NotificationTracker
{
    public $isNotificationTrackable;
    public $notificationTrackerUlid;
    public $notificationTrackerSender;

    // track notification
    public function trackNotification($trackable = true) : void
    {
        $this->isNotificationTrackable = $trackable;
        $this->notificationTrackerSender = user();
        $this->notificationTrackerUlid = (string) str()->ulid();
    }

    // failed handler
    public function failed(\Exception $e) : void
    {
        NotificationStatus::failed($this->notificationTrackerUlid, $e->getMessage());
    }
}