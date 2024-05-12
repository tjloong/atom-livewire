<?php

namespace Jiannius\Atom\Http\Livewire\App\File;

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
        'filesUploaded' => '$refresh',
    ];

    // get query property
    public function getQueryProperty() : mixed
    {
        return model('file')->whereNull('parent_id')->when(!$this->tableOrderBy, fn($q) => $q->latest());
    }

    // delete
    public function delete() : void
    {
        if ($this->tableCheckboxes) {
            model('file')->whereIn('id', $this->tableCheckboxes)->get()->each(fn($q) => $q->delete());
            $this->popup('app.alert.delete');
            $this->reset('tableCheckboxes');
        }
    }
}