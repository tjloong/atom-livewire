<?php

namespace Jiannius\Atom\Http\Livewire\App\Document\View;

use Illuminate\Database\Eloquent\Collection;
use Jiannius\Atom\Traits\Livewire\WithFile;
use Livewire\Component;

class Attachment extends Component
{
    use WithFile;

    public $document;

    /**
     * Get files property
     */
    public function getFilesProperty(): Collection
    {
        return $this->document->files()->orderBy('created_at')->get();
    }

    /**
     * Attach
     */
    public function attach($ids): void
    {
        foreach ($ids as $id) {
            if ($this->document->files()->find($id)) continue;
            $this->document->files()->attach($id);
        }
    }

    /**
     * Detach
     */
    public function detach($id): void
    {
        optional($this->document->files()->find($id))->delete();
    }

    /**
     * Render
     */
    public function render(): mixed
    {
        return atom_view('app.document.view.attachment');
    }
}