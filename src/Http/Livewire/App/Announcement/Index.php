<?php

namespace Jiannius\Atom\Http\Livewire\App\Announcement;

use Jiannius\Atom\Component;

class Index extends Component
{
    public $announcementId;

    protected $listeners = [
        'setAnnouncementId',
        'updateAnnouncement' => 'setAnnouncementId',
    ];

    // set announcement id
    public function setAnnouncementId($id = null) : void
    {
        $this->fill(['announcementId' => $id ?: null]);
    }
}