<?php

namespace Jiannius\Atom\Http\Livewire\App\File;

use Jiannius\Atom\Traits\Livewire\WithForm;
use Jiannius\Atom\Traits\Livewire\WithPopupNotify;
use Livewire\Component;

class FormModal extends Component
{
    use WithForm;
    use WithPopupNotify;

    public $file;

    protected $listeners = ['open'];

    /**
     * Validation
     */
    protected function validation(): array
    {
        return [
            'file.name' => ['required' => 'File name is required.'],
            'file.data.alt' => ['nullable'],
            'file.data.description' => ['nullable'],
        ];
    }

    /**
     * Open
     */
    public function open($id): void
    {
        $this->file = model('file')->findOrFail($id);
        $this->dispatchBrowserEvent('file-form-modal-open');
    }

    /**
     * Delete
     */
    public function delete($id): void
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
    public function submit(): void
    {
        $this->validateForm();

        $this->file->save();

        $this->emitUp('refresh');
        $this->dispatchBrowserEvent('file-form-modal-close');
    }

    /**
     * Render
     */
    public function render(): mixed
    {
        return atom_view('app.file.form-modal');
    }
}