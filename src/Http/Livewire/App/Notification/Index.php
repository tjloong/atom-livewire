<?php

namespace Jiannius\Atom\Http\Livewire\App\Notification;

use Jiannius\Atom\Component;

class Index extends Component
{
    public $channel;
    public $notificationId;

    protected $listeners = [
        'setNotificationId',
        'showNotification' => 'setNotificationId',
    ];

    // mount
    public function mount()
    {
        $this->channel = collect([
            'mail' => current_route('app.notification.mail'),
        ])->filter()->keys()->first();
    }

    // set notification id
    public function setNotificationId($id = null) : void
    {
        $this->fill(['notificationId' => $id ?: null]);
    }
}