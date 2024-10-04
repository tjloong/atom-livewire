<?php

namespace Jiannius\Atom\Http\Livewire\App\File;

use Jiannius\Atom\Atom;
use Jiannius\Atom\Component;
use Jiannius\Atom\Traits\Livewire\WithForm;

class Edit extends Component
{
    use WithForm;

    public $file;
    public $inputs;

    // validation
    protected function validation(): array
    {
        return [
            'file.name' => ['required' => 'File name is required.'],
        ];
    }

    // open
    public function open($id) : void
    {
        if ($this->file = model('file')->find($id)) {
            $this->fill([
                'inputs.alt' => data_get($this->file->data, 'alt'),
                'inputs.description' => data_get($this->file->data, 'description'),
            ]);
        }
    }

    // close
    public function close() : void
    {
        $this->resetValidation();
        $this->emit('fileSaved');
        Atom::modal('edit-file')->close();
    }

    // delete
    public function delete() : void
    {
        $this->file->delete();
        $this->close();
    }

    // submit
    public function submit() : void
    {
        $this->validateForm();
        
        $this->file->fill([
            'data' => [
                ...(array) $this->file->data,
                ...$this->inputs,
            ],
        ])->save();

        $this->close();
    }
}