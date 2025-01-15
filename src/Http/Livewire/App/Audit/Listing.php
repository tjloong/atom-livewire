<?php

namespace Jiannius\Atom\Http\Livewire\App\Audit;

use Livewire\Component;
use Jiannius\Atom\Traits\Livewire\WithTable;

class Listing extends Component
{
    use WithTable;

    public $filters = [
        'search' => null,
    ];

    // mount
    public function mount()
    {
        $this->filters = [
            ...$this->filters,
            ...(request()->query('filters') ?? []),
        ];
    }

    // get query property
    public function getQueryProperty() : mixed
    {
        return model('audit')->with('user')->when(!$this->tableOrderBy, fn($q) => $q->latest());
    }
}