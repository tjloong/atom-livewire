<?php

namespace Jiannius\Atom\Http\Livewire\App\Page;

use Livewire\Component;
use Jiannius\Atom\Traits\Livewire\WithTable;

class Listing extends Component
{
    use WithTable;

    public $filters = [
        'search' => null,
    ];

    protected $listeners = [
        'closePage' => '$refresh',
    ];

    // get query property
    public function getQueryProperty() : mixed
    {
        return model('page')->when(!$this->tableOrderBy, fn($q) => $q->latest());
    }
}