<?php

namespace Jiannius\Atom\Http\Livewire\App\Settings;

use Jiannius\Atom\Component;
use Jiannius\Atom\Traits\Livewire\WithTable;

class File extends Component
{
    use WithTable;

    public $filters = [
        'mime' => null,
        'search' => null,
    ];

    protected $listeners = [
        'uploaded' => '$refresh',
        'closeFile' => '$refresh',
    ];

    // get query property
    public function getQueryProperty() : mixed
    {
        return model('file')
            ->whereNull('parent_id')
            ->when(!$this->tableOrderBy, fn($q) => $q->latest());
    }

    // get storage used property
    public function getStorageUsedProperty() : string
    {
        return format(
            model('file')->sum('size')
        )->filesize('KB');
    }

    // delete
    public function delete() : void
    {
        if (!$this->tableCheckboxes) return;

        model('file')->whereIn('id', $this->tableCheckboxes)->get()->each(fn($file) => $file->delete());
        $this->reset('tableCheckboxes');
    }
}