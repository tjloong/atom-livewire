<?php

namespace Jiannius\Atom\Http\Livewire\App\Settings\File;

use Jiannius\Atom\Component;
use Jiannius\Atom\Traits\Livewire\WithTable;

class Index extends Component
{
    use WithTable;

    public $sort;
    public $files;

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
            ->when(!$this->sort, fn($q) => $q->latest('updated_at'));
    }

    // updated files
    public function updatedFiles() : void
    {
        $this->emit('fileUploaded');
        $this->reset('files');
    }

    // delete
    public function delete() : void
    {
        if ($this->checkboxes) {
            model('file')->whereIn('id', $this->checkboxes)->get()->each(fn($q) => $q->delete());

            $this->popup(count($this->checkboxes).' Files Deleted');
            $this->reset('checkboxes');
        }
    }
}