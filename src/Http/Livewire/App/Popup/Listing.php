<?php

namespace Jiannius\Atom\Http\Livewire\App\Popup;

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
        'popupCreated' => '$refresh',
        'popupUpdated' => '$refresh',
        'popupDeleted' => '$refresh',
    ];

    // get query property
    public function getQueryProperty() : mixed
    {
        return model('popup')
            ->filter($this->filters)
            ->when(!$this->tableSortOrder, fn($q) => $q->latest());
    }
}