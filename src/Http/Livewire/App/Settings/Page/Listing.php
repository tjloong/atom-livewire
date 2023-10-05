<?php

namespace Jiannius\Atom\Http\Livewire\App\Settings\Page;

use Jiannius\Atom\Component;
use Jiannius\Atom\Traits\Livewire\WithTable;

class Listing extends Component
{
    use WithTable;

    public $filters = [
        'search' => null,
    ];

    protected $listeners = [
        'pageCreated' => '$refresh',
        'pageUpdated' => '$refresh',
        'pageDeleted' => '$refresh',
    ];

    // get query property
    public function getQueryProperty() : mixed
    {
        return model('page')
            ->filter($this->filters)
            ->when(!$this->tableSortOrder, fn($q) => $q->latest());
    }
}