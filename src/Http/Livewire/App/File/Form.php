<?php

namespace Jiannius\Atom\Http\Livewire\App\File;

use Livewire\Component;

class Form extends Component
{
    public $file;

    protected $rules = [
        'file.name' => 'required',
        'file.data.alt' => 'nullable',
        'file.data.description' => 'nullable',
    ];

    /**
     * Mount
     */
    public function mount($fileId)
    {
        $this->file = model('file')->find($fileId);
    }

    /**
     * Updated file
     */
    public function updatedFile()
    {
        $this->file->save();
        $this->emitUp('saved');
    }

    /**
     * Rendering
     */
    public function render()
    {
        return view('atom::app.file.form');
    }
}