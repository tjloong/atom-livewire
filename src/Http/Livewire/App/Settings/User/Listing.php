<?php

namespace Jiannius\Atom\Http\Livewire\App\Settings\User;

use Jiannius\Atom\Component;
use Jiannius\Atom\Traits\Livewire\WithLoginMethods;
use Jiannius\Atom\Traits\Livewire\WithTable;

class Listing extends Component
{
    use WithLoginMethods;
    use WithTable;

    public $filters;

    protected $listeners = [
        'userCreated' => '$refresh',
        'userUpdated' => '$refresh',
        'userDeleted' => '$refresh',
    ];

    // mount
    public function mount() : void
    {
        $this->fill(['filters' => [
            'search' => null,
            'status' => enum('user.status', 'ACTIVE')->value,
            'role_id' => null,
        ]]);
    }

    // get query property
    public function getQueryProperty() : mixed
    {
        return model('user')
            ->filter($this->filters)
            ->when(!$this->tableSortOrder, fn($q) => $q->latest());
    }
}