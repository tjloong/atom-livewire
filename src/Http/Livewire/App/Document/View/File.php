<?php

namespace Jiannius\Atom\Http\Livewire\App\Document\View;

use Jiannius\Atom\Traits\Livewire\WithFile;
use Livewire\Component;

class File extends Component
{
    use WithFile;

    public $document;

    /**
     * Get files property
     */
    public function getFilesProperty()
    {
        return $this->document->files()->orderBy('created_at')->get();
    }

    /**
     * Attach
     */
    public function attach($ids)
    {
        foreach ($ids as $id) {
            if ($this->document->files()->find($id)) continue;
            $this->document->files()->attach($id);
        }
    }

    /**
     * Detach
     */
    public function detach($id)
    {
        optional($this->document->files()->find($id))->delete();
    }

    /**
     * Render
     */
    public function render()
    {
        return atom_view('app.document.view.file');
    }
}