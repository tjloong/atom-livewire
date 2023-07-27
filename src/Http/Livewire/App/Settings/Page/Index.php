<?php

namespace Jiannius\Atom\Http\Livewire\App\Settings\Page;

use Jiannius\Atom\Component;
use Jiannius\Atom\Traits\Livewire\WithTable;

class Index extends Component
{
    use WithTable;

    public $sort;

    public $filters = [
        'search' => null,
    ];

    protected $listeners = [
        'pageUpdateClosed' => '$refresh',
    ];

    // get pages property
    public function getPagesProperty(): mixed
    {
        return model('page')
            ->filter($this->filters)
            ->when(!$this->sort, fn($q) => $q->latest())
            ->get();
    }

    // update
    public function update($id): void
    {
        $this->emitTo('app.settings.page.update', 'open', $id);
    }
}