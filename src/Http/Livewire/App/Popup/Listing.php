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
        'closePopup' => '$refresh',
    ];

    // get query property
    public function getQueryProperty() : mixed
    {
        return model('popup')
            ->filter($this->filters)
            ->when(!$this->tableOrderBy, fn($q) => $q->latest());
    }
}