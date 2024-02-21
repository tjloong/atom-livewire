<?php

namespace Jiannius\Atom\Http\Livewire\App\User;

use Jiannius\Atom\Component;
use Jiannius\Atom\Traits\Livewire\WithLoginMethods;
use Jiannius\Atom\Traits\Livewire\WithTable;

class Listing extends Component
{
    use WithLoginMethods;
    use WithTable;

    public $filters = [
        'search' => null,
        'status' => null,
        'role_id' => null,
    ];

    protected $listeners = [
        'userCreated' => '$refresh',
        'userUpdated' => '$refresh',
        'userDeleted' => '$refresh',
    ];

    // mount
    public function mount() : void
    {
        $this->fill(['filters.status' => enum('user.status', 'ACTIVE')->value]);
    }

    // get query property
    public function getQueryProperty() : mixed
    {
        return model('user')
            ->filter($this->filters)
            ->when(!$this->tableOrderBy, fn($q) => $q->latest());
    }
}