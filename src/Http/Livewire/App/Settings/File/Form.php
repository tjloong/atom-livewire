<?php

namespace Jiannius\Atom\Http\Livewire\App\Settings\File;

use Jiannius\Atom\Traits\Livewire\WithForm;
use Jiannius\Atom\Traits\Livewire\WithPopupNotify;
use Livewire\Component;

class Form extends Component
{
    use WithForm;
    use WithPopupNotify;

    public $file;

    protected $listeners = ['open'];

    // validation
    protected function validation(): array
    {
        return [
            'file.name' => ['required' => 'File name is required.'],
            'file.data.alt' => ['nullable'],
            'file.data.description' => ['nullable'],
        ];
    }

    // open
    public function open($id): void
    {
        $this->file = model('file')->findOrFail($id);
        $this->dispatchBrowserEvent('file-form-open');
    }

    // close
    public function close(): void
    {
        $this->emit('refresh');
        $this->dispatchBrowserEvent('file-form-close');
    }

    // delete
    public function delete($id): void
    {
        if ($file = model('file')->find($id)) {
            $file->delete();
            $this->popup('File Deleted');
        }

        $this->close();
    }

    // submit
    public function submit(): void
    {
        $this->validateForm();
        $this->file->save();
        $this->close();
    }

    // render
    public function render(): mixed
    {
        return atom_view('app.settings.file.form');
    }
}