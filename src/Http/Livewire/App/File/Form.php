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
     * Open
     */
    public function open($id)
    {
        $this->file = model('file')->find($id);
        $this->dispatchBrowserEvent('file-form-open');
    }

    /**
     * Delete
     */
    public function delete($id)
    {
        $this->emitUp('delete', $id);
    }

    /**
     * Submit
     */
    public function submit()
    {
        $this->file->save();
        $this->emitUp('saved');
        $this->dispatchBrowserEvent('file-form-close');
    }

    /**
     * Rendering
     */
    public function render()
    {
        return atom_view('app.file.form');
    }
}