<?php

namespace Jiannius\Atom\Http\Livewire\App\Settings\User;

use Jiannius\Atom\Component;
use Jiannius\Atom\Traits\Livewire\WithLoginMethods;
use Jiannius\Atom\Traits\Livewire\WithPopupNotify;
use Jiannius\Atom\Traits\Livewire\WithTable;

class Index extends Component
{
    use WithLoginMethods;
    use WithPopupNotify;
    use WithTable;

    public $sort;

    public $filters = [
        'search' => null,
        'status' => null,
        'role_id' => null,
        'team_id' => null,
    ];

    protected $listeners = [
        'userSaved' => '$refresh',
    ];

    // get query property
    public function getQueryProperty(): mixed
    {
        return model('user')
            ->readable()
            ->filter($this->filters)
            ->when(!$this->sort, fn($q) => $q->latest());
    }

    // empty trashed
    public function emptyTrashed(): void
    {
        $this->query->onlyTrashed()->forceDelete();
    }
}