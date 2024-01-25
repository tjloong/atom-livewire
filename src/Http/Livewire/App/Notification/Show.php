<?php

namespace Jiannius\Atom\Http\Livewire\App\Notification;

use Jiannius\Atom\Component;

class Show extends Component
{
    public $notification;

    protected $listeners = [
        'showNotification' => 'open',
    ];

    // open
    public function open($ulid) : void
    {
        if ($this->notification = model('notification')->where('ulid', $ulid)->first()) {
            $this->openDrawer('notification-show');
        }
    }

    // close
    public function close() : void
    {
        $this->emit('setNotificationId');
        $this->closeDrawer('notification-show');
    }

    // delete
    public function delete() : void
    {
        $this->notification->delete();
        $this->emit('notificationDeleted');
        $this->close();
    }
}