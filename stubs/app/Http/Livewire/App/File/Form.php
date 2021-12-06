<?php

namespace App\Http\Livewire\App\File;

use App\Models\File;
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
     * Rendering livewire view
     * 
     * @return Response
     */
    public function render()
    {
        return view('livewire.app.file.form');
    }

    /**
     * Save file
     * 
     * @return void
     */
    public function updatedFile()
    {
        $this->file->save();
        $this->emitUp('saved');
    }

    /**
     * Delete file
     * 
     * @return void
     */
    public function delete()
    {
        $this->file->delete();
        $this->dispatchBrowserEvent('toast', ['message' => 'File Deleted']);
        $this->emitUp('deleted');
    }
}