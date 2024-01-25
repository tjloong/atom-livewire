<?php

namespace Jiannius\Atom\Http\Livewire\App\Notification;

use Jiannius\Atom\Component;
use Jiannius\Atom\Traits\Livewire\WithTable;

class Listing extends Component
{
    use WithTable;

    public $channel;

    public $filters = [
        'search' => null,
        'status' => null,
    ];

    protected $listeners = [
        'notificationDeleted' => '$refresh',
    ];

    // get query property
    public function getQueryProperty() : mixed
    {
        return model('notification')
            ->where('channel', $this->channel)
            ->filter($this->filters)
            ->when(!$this->tableSortOrder, fn($q) => $q->latest());
    }

    // delete
    public function delete() : void
    {
        if ($this->checkboxes) {
            model('notification')->whereIn('id', $this->checkboxes)->delete();
            $this->reset('checkboxes');
            $this->emit('notificationDeleted');
        }
    }
}