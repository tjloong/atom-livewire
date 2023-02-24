<?php

namespace Jiannius\Atom\Http\Livewire\App\File;

use Jiannius\Atom\Traits\Livewire\WithPopupNotify;
use Livewire\Component;

class FormModal extends Component
{
    use WithPopupNotify;

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
            'file.name.required' => 'File name is required.',
        ];
    }

    /**
     * Open
     */
    public function open($id)
    {
        $this->file = model('file')->findOrFail($id);
        $this->dispatchBrowserEvent('file-form-modal-open');
    }

    /**
     * Delete
     */
    public function delete($id)
    {
        if ($file = model('file')->find($id)) {
            $file->delete();
            
            $this->popup('File Deleted');
            $this->emitUp('refresh');
            $this->dispatchBrowserEvent('file-form-modal-close');
        }
    }

    /**
     * Submit
     */
    public function submit()
    {
        $this->file->save();
        $this->emitUp('refresh');
        $this->dispatchBrowserEvent('file-form-modal-close');
    }

    /**
     * Render
     */
    public function render()
    {
        return atom_view('app.file.form-modal');
    }
}