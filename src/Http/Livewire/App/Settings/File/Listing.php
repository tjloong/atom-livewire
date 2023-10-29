<?php

namespace Jiannius\Atom\Http\Livewire\App\Settings\File;

use Jiannius\Atom\Component;
use Jiannius\Atom\Traits\Livewire\WithTable;

class Listing extends Component
{
    use WithTable;

    public $filters = [
        'mime' => null,
        'search' => null,
    ];

    protected $listeners = [
        'fileUpdated' => '$refresh',
        'fileDeleted' => '$refresh',
        'fileUploaded' => '$refresh',
    ];

    // get query property
    public function getQueryProperty() : mixed
    {
        return model('file')
            ->filter($this->filters)
            ->whereNull('parent_id')
            ->when(!$this->tableSortOrder, fn($q) => $q->latest());
    }

    // delete
    public function delete() : void
    {
        if ($this->checkboxes) {
            model('file')->whereIn('id', $this->checkboxes)->get()->each(fn($q) => $q->delete());
            $this->popup('file.alert.delete');
            $this->reset('checkboxes');
        }
    }
}