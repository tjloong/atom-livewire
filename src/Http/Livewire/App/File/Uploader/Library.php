<?php

namespace Jiannius\Atom\Http\Livewire\App\File\Uploader;

use Livewire\Component;

class Library extends Component
{
    public $page = 1;
    public $accept;
    public $multiple;
    public $selected = [];
    public $filters = [
        'search' => null,
    ];

    /**
     * Get files property
     */
    public function getFilesProperty()
    {
        return model('file')
            ->type($this->accept)
            ->when(data_get($this->filters, 'search'), fn($q, $search) => $q->search($search))
            ->orderBy('created_at', 'desc')
            ->paginate(40, ['*'], 'page', $this->page);
    }

    /**
     * Select
     */
    public function select($id)
    {
        $selected = collect($this->selected);

        if ($selected->contains($id)) $selected = $selected->reject($id);
        else if ($this->multiple) $selected->push($id);
        else $selected = collect([$id]);

        $this->selected = $selected->toArray();
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
        return atom_view('app.file.uploader.library');
    }
}