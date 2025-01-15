<?php

namespace Jiannius\Atom\Http\Livewire\App;

use Livewire\Component;

class Dashboard extends Component
{
    public $filters = [
        'date' => null,
    ];

    // mount
    public function mount()
    {
        $this->fill([
            'filters.date' => collect([
                today()->local()->startOfDay()->subDays(30)->utc()->toDatetimeString(),
                now()->toDatetimeString(),
            ])->filter()->join(' to '),
        ]);
    }

    // updated filters
    public function updatedFilters() : void
    {
        $this->refreshKeynonce();
    }
}