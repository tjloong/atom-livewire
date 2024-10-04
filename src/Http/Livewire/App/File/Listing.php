<?php

namespace Jiannius\Atom\Http\Livewire\App\File;

use Jiannius\Atom\Component;

class Listing extends Component
{
    public $filters = [
        'mime' => null,
        'search' => null,
    ];

    protected $listeners = [
        'uploaded' => '$refresh',
        'fileSaved' => '$refresh',
    ];

    // get files property
    public function getFilesProperty()
    {
        return $this->getTable(
            query: model('file')->whereNull('parent_id'),
        );
    }

    // delete
    public function delete() : void
    {
        if ($id = get($this->table, 'checkboxes')) {
            model('file')->whereIn('id', $id)->get()->each(fn($file) => $file->delete());
            $this->fill(['table.checkboxes' => []]);
        }
    }
}