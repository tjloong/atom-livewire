<?php

namespace Jiannius\Atom\Http\Livewire\App\File\Uploader;

use Livewire\Component;

class Library extends Component
{
    public $page = 1;
    public $search;
    public $accept;
    public $multiple;
    public $selected;

    /**
     * Mount
     */
    public function mount()
    {
        //
    }

    /**
     * Get files property
     */
    public function getFilesProperty()
    {
        return model('file')
            ->type($this->accept)
            ->when($this->search, fn($q) => $q->search($this->search))
            ->orderBy('created_at', 'desc')
            ->paginate(40, ['*'], 'page', $this->page);
    }

    /**
     * Select
     */
    public function select($id)
    {
        if (!$this->selected) $this->selected = collect([]);

        if ($this->multiple) {
            if ($this->selected->contains($id)) $this->selected = $this->selected->reject(fn($val) => $val === $id);
            else $this->selected->push($id);
        }
        else $this->selected = [$id];
    }

    /**
     * Submit
     */
    public function submit()
    {
        $files = model('file')->whereIn('id', $this->selected)->get();

        $this->emitUp('completed', $files);
    }

    /**
     * Render
     */
    public function render()
    {
        return view('atom::app.file.uploader.library');
    }
}