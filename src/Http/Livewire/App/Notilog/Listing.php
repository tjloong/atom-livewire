<?php

namespace Jiannius\Atom\Http\Livewire\App\Notilog;

use Jiannius\Atom\Component;
use Jiannius\Atom\Traits\Livewire\WithTable;

class Listing extends Component
{
    use WithTable;

    public $filters = [
        'search' => null,
        'channel' => [],
        'status' => [],
        'tags' => [],
    ];

    protected $listeners = [
        'notilogDeleted' => '$refresh',
    ];

    // get query property
    public function getQueryProperty() : mixed
    {
        return model('notilog')
            ->filter($this->filters)
            ->when(!$this->tableSortOrder, fn($q) => $q->latest());
    }

    // delete
    public function delete() : void
    {
        if ($this->checkboxes) {
            model('notilog')->whereIn('id', $this->checkboxes)->delete();
            $this->reset('checkboxes');
            $this->emit('notilogDeleted');
        }
    }
}