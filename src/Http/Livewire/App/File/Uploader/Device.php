<?php

namespace Jiannius\Atom\Http\Livewire\App\File\Uploader;

use Livewire\Component;
use Livewire\WithFileUploads;

class Device extends Component
{
    use WithFileUploads;
    
    public $files;
    public $private;
    public $multiple;
    public $inputFileTypes;

    /**
     * Updated files
     */
    public function updatedFiles()
    {
        $completed = [];
        $location = $this->private ? 'uploads' : 'public/uploads';

        foreach ($this->files as $file) {
            array_push($completed, model('file')->store($file, $location));
        }

        $this->emitUp('completed', $completed);
    }

    /**
     * Render
     */
    public function render()
    {
        return view('atom::app.file.uploader.device');
    }
}