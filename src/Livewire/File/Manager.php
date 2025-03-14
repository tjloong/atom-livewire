<?php

namespace Jiannius\Atom\Livewire\File;

use Jiannius\Atom\Traits\Livewire\AtomComponent;
use Livewire\Component;

class Manager extends Component
{
    use AtomComponent;

    public $filters = [];

    protected $listeners = [
        'uploaded' => '$refresh',
    ];

    public function mount()
    {
        $this->filters = [
            'mime' => null,
            'search' => null,
            ...$this->filters,
        ];
    }

    public function getStorageProperty() : string
    {
        $sum = model('file')->sum('kb');
        return num()->filesize($sum, 'KB');
    }

    public function getFilesProperty()
    {
        return $this->getTable(
            query: model('file')
                ->filter($this->filters)
                ->whereNull('parent_id'),
        );
    }

    public function delete() : void
    {
        if ($id = get($this->table, 'checkboxes')) {
            model('file')->whereIn('id', $id)->get()->each(fn($file) => $file->delete());
            $this->fill(['table.checkboxes' => []]);
        }
    }
}