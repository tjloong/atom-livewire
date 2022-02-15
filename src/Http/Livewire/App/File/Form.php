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
     * 
     * @return void
     */
    public function mount()
    {
        //
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
     * Delete
     */
    public function delete()
    {
        $this->file->delete();
        $this->dispatchBrowserEvent('toast', ['message' => 'File Deleted']);
        $this->emitUp('deleted');
    }

    /**
     * Rendering livewire view
     * 
     * @return Response
     */
    public function render()
    {
        return view('atom::app.file.form');
    }
}