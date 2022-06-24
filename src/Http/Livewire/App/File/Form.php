<?php

namespace Jiannius\Atom\Http\Livewire\App\File;

use Livewire\Component;

class Form extends Component
{
    public $file;

    protected $listeners = ['open'];

    /**
     * Validation rules
     */
    protected function rules()
    {
        return [
            'file.name' => 'required',
            'file.data.alt' => 'nullable',
            'file.data.description' => 'nullable',
        ];
    }

    /**
     * Validation messages
     */
    protected function messages()
    {
        return [
            'file.name.required' => __('File name is required.'),
        ];
    }

    /**
     * Mount
     */
    public function mount()
    {
        //
    }

    /**
     * Open
     */
    public function open($id)
    {
        $this->file = model('file')->find($id);
        $this->dispatchBrowserEvent('file-form-open');
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