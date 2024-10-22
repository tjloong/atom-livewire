<?php

namespace Jiannius\Atom\Http\Livewire\App\File;

use Jiannius\Atom\Traits\Livewire\AtomComponent;
use Livewire\Component;

class Listing extends Component
{
    use AtomComponent;

    public $filters = [
        'mime' => null,
        'search' => null,
    ];

    protected $listeners = [
        'uploaded' => '$refresh',
    ];

    public function getFilesProperty()
    {
        return $this->getTable(
            query: model('file')->whereNull('parent_id'),
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