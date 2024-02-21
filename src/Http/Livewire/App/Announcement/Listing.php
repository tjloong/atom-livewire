<?php

namespace Jiannius\Atom\Http\Livewire\App\Announcement;

use Jiannius\Atom\Component;
use Jiannius\Atom\Traits\Livewire\WithTable;

class Listing extends Component
{
    use WithTable;

    public $filters = [
        'search' => null,
        'status' => [],
    ];

    protected $listeners = [
        'announcementCreated' => '$refresh',
        'announcementUpdated' => '$refresh',
        'announcementDeleted' => '$refresh',
    ];

    // get query property
    public function getQueryProperty() : mixed
    {
        return model('announcement')
            ->filter($this->filters)
            ->when(!$this->tableOrderBy, fn($q) => $q->latest());
    }
}